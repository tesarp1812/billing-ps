<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import AppLayout from '../../Layouts/AppLayout.vue';
import StatCard from '../../components/ui/StatCard.vue';
import StationCard from '../../components/dashboard/StationCard.vue';
import BillingDetailModal from '../../components/billing/BillingDetailModal.vue';
import { useDashboardStore } from '../../stores/dashboard';
import { useCurrency } from '../../composables/useCurrency';
import { stationService } from '../../services/stationService';

const dashboardStore = useDashboardStore();
const { formatCurrency } = useCurrency();

const selectedStation = ref(null);
const detailOpen = ref(false);
let refreshTimer;
let clockTimer;

const now = ref(Date.now());

const openDetail = (station) => {
    selectedStation.value = station;
    detailOpen.value = true;
};

const performAction = async (handler) => {
    await handler();
    await dashboardStore.fetchSummary();

    if (selectedStation.value) {
        selectedStation.value = dashboardStore.stations.find((station) => station.id === selectedStation.value.id) || null;
    }
};

const startStation = async (station) => {
    const customerName = window.prompt(`Customer untuk ${station.name}`, station.current_session?.customer_name || '');
    if (customerName === null) return;
    
    const packageInput = window.prompt('Paket menit (kosong/0 = Free / tanpa paket)', '0');
    if (packageInput === null) return;
    
    const packageMinutes = parseInt(packageInput) || 0;

    await performAction(() => stationService.start(station.id, { 
        customer_name: customerName || null,
        package_minutes: packageMinutes
    }));
};

const pauseStation = async (station) => {
    await performAction(() => stationService.pause(station.id));
};

const stopStation = async (station) => {
    await performAction(() => stationService.stop(station.id));
};

const addTime = async (minutes) => {
    if (!selectedStation.value) {
        return;
    }

    await performAction(() => stationService.addTime(selectedStation.value.id, minutes));
};

onMounted(async () => {
    await dashboardStore.fetchSummary()

    refreshTimer = window.setInterval(() => {
        dashboardStore.fetchSummary()
    }, 60000)

    clockTimer = window.setInterval(() => {
        now.value = Date.now()
    }, 1000)
});

onBeforeUnmount(() => {
    window.clearInterval(refreshTimer)
    window.clearInterval(clockTimer)
});
</script>

<template>
    <AppLayout>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <StatCard label="Omzet Hari Ini" :value="formatCurrency(dashboardStore.summary?.today_revenue)" hint="Akumulasi transaksi hari ini" />
            <StatCard label="Transaksi" :value="dashboardStore.summary?.today_transactions || 0" hint="Jumlah checkout selesai" />
            <StatCard label="Station Aktif" :value="dashboardStore.summary?.active_stations || 0" hint="Playing, paused, dan booking" />
            <StatCard label="Station Kosong" :value="dashboardStore.summary?.empty_stations || 0" hint="Siap dipakai customer berikutnya" />
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-[2fr,1fr]">
            <section>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-display text-2xl uppercase tracking-[0.12em] text-white">Realtime Station</h3>
                    <button class="rounded-2xl border border-white/10 px-4 py-2 text-sm text-slate-300 hover:text-white" @click="dashboardStore.fetchSummary()">
                        Refresh
                    </button>
                </div>
                <div class="grid gap-4 md:grid-cols-2 2xl:grid-cols-3">
                    <StationCard
                        v-for="station in dashboardStore.stations"
                        :key="station.id"
                        :station="station"
                        @detail="openDetail"
                        @start="startStation"
                        @pause="pauseStation"
                        @stop="stopStation"
                    />
                </div>
            </section>

            <section class="space-y-4">
                <div class="rounded-[28px] border border-white/10 bg-white/5 p-5">
                    <h3 class="font-display text-2xl uppercase tracking-[0.12em] text-white">Jam Ramai</h3>
                    <div class="mt-5 space-y-3">
                        <div v-for="hour in dashboardStore.summary?.busy_hours || []" :key="hour.hour" class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                            <div class="flex items-center justify-between text-sm text-slate-400">
                                <span>{{ hour.hour }}</span>
                                <span>{{ formatCurrency(hour.total) }}</span>
                            </div>
                            <div class="mt-3 h-2 rounded-full bg-slate-800">
                                <div class="h-2 rounded-full bg-gradient-to-r from-cyan-400 to-violet-500" :style="{ width: `${Math.min(100, (hour.total / (dashboardStore.summary?.today_revenue || 1)) * 100)}%` }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <BillingDetailModal
            :open="detailOpen"
            :station="selectedStation"
            @close="detailOpen = false"
            @add-time="addTime"
        />
    </AppLayout>
</template>
