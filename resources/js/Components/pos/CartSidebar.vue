<script setup>
import { computed, ref } from 'vue';
import { useCurrency } from '../../composables/useCurrency';

const props = defineProps({
    cart: Array,
    total: Number,
    stations: Array,
});

const emit = defineEmits(['update-qty', 'remove', 'checkout']);
const { formatCurrency } = useCurrency();

const stationId = ref('');
const paymentMethod = ref('cash');
const paidAmount = ref(0);

const selectedStation = computed(() =>
    props.stations.find((station) => String(station.id) === String(stationId.value)) || null
);

const billingTotal = computed(() => Number(selectedStation.value?.current_session?.subtotal || 0));
const grandTotal = computed(() => Number(props.total || 0) + billingTotal.value);
const canCheckout = computed(() => props.cart.length > 0 || stationId.value);

const submitCheckout = () => {
    emit('checkout', {
        station_id: stationId.value || null,
        payment_method: paymentMethod.value,
        paid_amount: Number(paidAmount.value || grandTotal.value),
    });
};
</script>

<template>
    <div class="rounded-[28px] border border-white/10 bg-white/5 p-5">
        <div class="flex items-center justify-between">
            <h3 class="font-display text-2xl uppercase tracking-[0.12em] text-white">Cart</h3>
            <span class="rounded-full bg-cyan-400/10 px-3 py-1 text-xs font-semibold text-cyan-200">{{ cart.length }} item</span>
        </div>

        <div class="mt-5 space-y-3">
            <div v-for="item in cart" :key="item.id" class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="font-semibold text-white">{{ item.name }}</div>
                        <div class="text-sm text-slate-400">{{ formatCurrency(item.price) }}</div>
                    </div>
                    <button class="text-sm text-rose-300" @click="emit('remove', item.id)">Hapus</button>
                </div>
                <div class="mt-3 flex items-center gap-3">
                    <input
                        :value="item.qty"
                        type="number"
                        min="1"
                        class="w-20 rounded-2xl border border-white/10 bg-slate-900 px-3 py-2 text-white"
                        @input="emit('update-qty', item.id, Number($event.target.value))"
                    >
                    <div class="text-sm text-slate-400">{{ formatCurrency(item.price * item.qty) }}</div>
                </div>
            </div>
        </div>

        <div class="mt-6 space-y-4 rounded-3xl border border-white/10 bg-slate-950/40 p-4">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-300">Gabungkan Billing Station</label>
                <select v-model="stationId" class="block w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white">
                    <option value="">Tanpa station</option>
                    <option v-for="station in stations" :key="station.id" :value="station.id">
                        {{ station.name }} - {{ station.status }}
                    </option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-300">Metode Bayar</label>
                <select v-model="paymentMethod" class="block w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white">
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                    <option value="debit">Debit</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-300">Jumlah Bayar</label>
                <input v-model="paidAmount" type="number" min="0" class="block w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white">
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div>
                <div class="text-sm text-slate-500">Billing station</div>
                <div class="text-lg font-semibold text-white">{{ formatCurrency(billingTotal) }}</div>
                <div class="text-sm text-slate-400">Total</div>
                <div class="font-display text-3xl text-cyan-300">{{ formatCurrency(grandTotal) }}</div>
            </div>
            <button :disabled="!canCheckout" class="rounded-2xl bg-gradient-to-r from-cyan-400 via-sky-500 to-violet-500 px-5 py-3 font-semibold text-slate-950 disabled:cursor-not-allowed disabled:opacity-40" @click="submitCheckout">
                Checkout
            </button>
        </div>
    </div>
</template>
