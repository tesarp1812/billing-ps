<script setup>
import { useCurrency } from '../../composables/useCurrency';

defineProps({
    products: Array,
});

const emit = defineEmits(['add']);
const { formatCurrency } = useCurrency();
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <button
            v-for="product in products"
            :key="product.id"
            class="rounded-[28px] border border-white/10 bg-white/5 p-5 text-left transition hover:-translate-y-1 hover:border-cyan-400/30 hover:bg-cyan-400/10"
            @click="emit('add', product)"
        >
            <div class="text-sm uppercase tracking-[0.28em] text-slate-400">{{ product.category }}</div>
            <div class="mt-3 text-2xl font-semibold text-white">{{ product.name }}</div>
            <div class="mt-4 flex items-center justify-between">
                <span class="font-display text-2xl text-cyan-300">{{ formatCurrency(product.price) }}</span>
                <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-300">Stock {{ product.stock }}</span>
            </div>
        </button>
    </div>
</template>
