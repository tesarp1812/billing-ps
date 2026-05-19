<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '../../layouts/AppLayout.vue';
import StatCard from '../../components/ui/StatCard.vue';
import { reportService } from '../../services/reportService';
import { useCurrency } from '../../composables/useCurrency';

const report = ref(null);
const { formatCurrency } = useCurrency();
const selectedDate = ref(new Date().toISOString().slice(0, 10));

const loadReport = async () => {
    report.value = await reportService.daily(selectedDate.value);
};

onMounted(loadReport);
</script>

<template>
    <AppLayout>
        <div class="mb-6 flex flex-col gap-4 rounded-[28px] border border-white/10 bg-white/5 p-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h3 class="font-display text-2xl uppercase tracking-[0.12em] text-white">Daily Reports</h3>
                <p class="mt-1 text-slate-400">Ringkasan omzet, transaksi, dan jam ramai per hari.</p>
            </div>
            <div class="flex items-center gap-3">
                <input v-model="selectedDate" type="date" class="rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white">
                <button class="rounded-2xl bg-gradient-to-r from-cyan-400 via-sky-500 to-violet-500 px-5 py-3 font-semibold text-slate-950" @click="loadReport">
                    Apply
                </button>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <StatCard label="Omzet" :value="formatCurrency(report?.revenue)" hint="Total penjualan dan billing" />
            <StatCard label="Transaksi" :value="report?.transactions || 0" hint="Jumlah checkout pada tanggal ini" />
            <StatCard label="Average" :value="formatCurrency(report?.average_transaction)" hint="Rata-rata nilai transaksi" />
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-2">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-5">
                <h4 class="font-display text-2xl uppercase tracking-[0.12em] text-white">Jam Ramai</h4>
                <div class="mt-5 space-y-4">
                    <div v-for="hour in report?.busy_hours || []" :key="hour.hour">
                        <div class="mb-2 flex items-center justify-between text-sm text-slate-400">
                            <span>{{ hour.hour }}</span>
                            <span>{{ hour.total_sessions }} sesi</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-800">
                            <div class="h-3 rounded-full bg-gradient-to-r from-cyan-400 to-violet-500" :style="{ width: `${Math.min(100, hour.total_sessions * 12)}%` }"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-[28px] border border-white/10 bg-white/5 p-5">
                <h4 class="font-display text-2xl uppercase tracking-[0.12em] text-white">Chart 7 Hari</h4>
                <div class="mt-5 flex h-72 items-end gap-3">
                    <div v-for="item in report?.seven_day_revenue || []" :key="item.date" class="flex flex-1 flex-col items-center gap-3">
                        <div class="w-full rounded-t-2xl bg-gradient-to-t from-cyan-500 to-violet-500" :style="{ height: `${Math.max(12, (item.total / Math.max(...(report?.seven_day_revenue || [{ total: 1 }]).map((entry) => entry.total || 1))) * 220)}px` }"></div>
                        <div class="text-center text-xs text-slate-400">{{ item.date.slice(5) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
