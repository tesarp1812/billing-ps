<script setup>
import { computed } from 'vue'
import BaseModal from '../ui/BaseModal.vue'
import { useCurrency } from '../../composables/useCurrency'
import { useStationTimer } from '../../composables/useStationTimer'

const props = defineProps({
    open: Boolean,
    station: Object,
})

const emit = defineEmits(['close', 'add-time'])

const { formatCurrency } = useCurrency()
const { tick, getDisplayTimer, getRealtimeSubtotal, isPackageMode, isExpired } = useStationTimer()

const isPlaying = computed(() => props.station?.status === 'playing')
const session = computed(() => props.station?.current_session)

const displayTimer = computed(() => {
    tick.value
    return getDisplayTimer(session.value, isPlaying.value)
})

const realtimeSubtotal = computed(() => {
    tick.value
    return getRealtimeSubtotal(session.value, isPlaying.value, props.station?.price_per_hour || 0)
})

const isPackage = computed(() => {
    tick.value
    return isPackageMode(session.value)
})
const expired = computed(() => isExpired(session.value, isPlaying.value))

const timerClass = computed(() => {
    if (expired.value) return 'text-rose-400'
    if (isPackage.value) return 'text-amber-300'
    return 'text-cyan-300'
})
</script>

<template>
    <BaseModal :open="open" title="Billing Detail" @close="emit('close')">
        <div v-if="station" class="space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <div class="text-sm uppercase tracking-[0.28em] text-slate-400">
                        Station
                    </div>

                    <div class="mt-2 font-display text-3xl uppercase tracking-[0.12em] text-white">
                        {{ station.name }}
                    </div>

                    <div class="mt-2 text-slate-400">
                        {{ station.type }} • {{ station.code }}
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <div class="text-sm uppercase tracking-[0.28em] text-slate-400">
                        <span v-if="isPackage">Sisa Waktu</span>
                        <span v-else>Durasi Berjalan</span>
                    </div>

                    <div class="mt-2 font-display text-3xl" :class="timerClass">
                        {{ displayTimer }}
                    </div>

                    <div class="mt-2 text-slate-400">
                        {{ formatCurrency(realtimeSubtotal) }}
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                <div class="text-sm text-slate-400">Customer</div>

                <div class="mt-1 text-xl font-semibold text-white">
                    {{ station.current_session?.customer_name || 'Walk-in' }}
                </div>

                <div class="mt-4 text-sm text-slate-400">Status</div>

                <div class="mt-1 text-lg font-semibold capitalize text-white">
                    {{ station.status }}
                </div>

                <div v-if="isPackage" class="mt-4 text-sm text-slate-400">Paket</div>
                <div v-if="isPackage" class="mt-1 text-lg font-semibold text-amber-200">
                    {{ station.current_session?.package_minutes }} menit
                </div>
            </div>

            <div class="grid gap-3 md:grid-cols-3">
                <button
                    class="rounded-2xl bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 hover:bg-white/10"
                    @click="emit('add-time', 30)"
                >
                    Tambah 30 Menit
                </button>

                <button
                    class="rounded-2xl bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 hover:bg-white/10"
                    @click="emit('add-time', 60)"
                >
                    Tambah 1 Jam
                </button>

                <button
                    class="rounded-2xl bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 hover:bg-white/10"
                    @click="emit('add-time', 120)"
                >
                    Tambah 2 Jam
                </button>
            </div>
        </div>
    </BaseModal>
</template>