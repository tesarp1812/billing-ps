<script setup>
import { computed } from 'vue'
import { useCurrency } from '../../composables/useCurrency'
import { useStationTimer } from '../../composables/useStationTimer'

const props = defineProps({
    station: Object,
})

const emit = defineEmits(['start', 'pause', 'stop', 'detail'])

const { formatCurrency } = useCurrency()
const { tick, getDisplayTimer, getRealtimeSubtotal, isPackageMode, isExpired } = useStationTimer()

const isPlaying = computed(() => props.station.status === 'playing')
const session = computed(() => props.station.current_session)

const displayTimer = computed(() => {
    tick.value
    return getDisplayTimer(session.value, isPlaying.value)
})

const realtimeSubtotal = computed(() => {
    tick.value
    return getRealtimeSubtotal(session.value, isPlaying.value, props.station.price_per_hour)
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

const statusClass = computed(() => ({
    empty: 'border-slate-700/80 bg-slate-900/80',
    playing: 'border-emerald-400/40 bg-emerald-400/10',
    paused: 'border-amber-400/40 bg-amber-400/10',
    booking: 'border-violet-400/40 bg-violet-400/10',
}[props.station.status] || 'border-slate-700/80 bg-slate-900/80'))
</script>

<template>
    <div
        class="rounded-[28px] border p-5 shadow-xl transition hover:-translate-y-1"
        :class="statusClass"
    >
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.28em] text-slate-400">
                    {{ station.type }}
                </p>

                <h3 class="mt-2 font-display text-3xl uppercase tracking-[0.12em] text-white">
                    {{ station.name }}
                </h3>

                <p class="mt-1 text-sm text-slate-400">
                    {{ formatCurrency(station.price_per_hour) }} / jam
                </p>
            </div>

            <button
                class="rounded-2xl border border-white/10 px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-300 hover:text-white"
                @click="emit('detail', station)"
            >
                Detail
            </button>
        </div>

        <div class="mt-6 rounded-3xl border border-white/10 bg-slate-950/45 p-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-400">Status</span>

                <span
                    class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em]"
                    :class="{
                        'bg-emerald-400/20 text-emerald-200': station.status === 'playing',
                        'bg-amber-400/20 text-amber-200': station.status === 'paused',
                        'bg-violet-400/20 text-violet-200': station.status === 'booking',
                        'bg-slate-700/60 text-slate-300': station.status === 'empty',
                    }"
                >
                    {{ station.status }}
                </span>
            </div>

            <div class="mt-4 text-sm text-slate-400">Customer</div>
            <div class="text-lg font-semibold text-white">
                {{ station.current_session?.customer_name || 'Walk-in' }}
            </div>

            <div class="mt-4 text-sm text-slate-400">
                <span v-if="isPackage">Sisa Waktu</span>
                <span v-else>Durasi Berjalan</span>
            </div>
            <div class="font-display text-4xl" :class="timerClass">
                {{ displayTimer }}
            </div>

            <div class="mt-4 text-sm text-slate-400">Subtotal</div>
            <div class="text-xl font-semibold text-white">
                {{ formatCurrency(realtimeSubtotal) }}
            </div>
        </div>

        <div class="mt-5 grid grid-cols-3 gap-3">
            <button
                class="rounded-2xl bg-cyan-400/20 px-4 py-3 text-sm font-semibold text-cyan-100 transition hover:bg-cyan-400/30"
                @click="emit('start', station)"
            >
                Start
            </button>

            <button
                class="rounded-2xl bg-amber-400/20 px-4 py-3 text-sm font-semibold text-amber-100 transition hover:bg-amber-400/30"
                @click="emit('pause', station)"
            >
                Pause
            </button>

            <button
                class="rounded-2xl bg-rose-400/20 px-4 py-3 text-sm font-semibold text-rose-100 transition hover:bg-rose-400/30"
                @click="emit('stop', station)"
            >
                Stop
            </button>
        </div>
    </div>
</template>