<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '../../layouts/AppLayout.vue';
import { transactionService } from '../../services/transactionService';
import { useCurrency } from '../../composables/useCurrency';

const transactions = ref([]);
const { formatCurrency } = useCurrency();

onMounted(async () => {
    transactions.value = await transactionService.list();
});
</script>

<template>
    <AppLayout>
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-5">
            <div class="mb-6">
                <h3 class="font-display text-2xl uppercase tracking-[0.12em] text-white">Transaction History</h3>
                <p class="mt-1 text-slate-400">50 transaksi terbaru untuk audit kasir dan billing.</p>
            </div>

            <div class="space-y-4">
                <div v-for="transaction in transactions" :key="transaction.id" class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <div class="font-display text-xl text-white">{{ transaction.code }}</div>
                            <div class="text-sm text-slate-400">{{ transaction.payment_method }} • {{ transaction.created_at }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-slate-400">Total</div>
                            <div class="font-display text-2xl text-cyan-300">{{ formatCurrency(transaction.total) }}</div>
                        </div>
                    </div>
                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        <div v-for="item in transaction.items" :key="item.id" class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                            <div class="flex items-center justify-between">
                                <span>{{ item.name }}</span>
                                <span>{{ item.item_type }}</span>
                            </div>
                            <div class="mt-2 text-slate-400">Qty {{ item.qty }} • {{ formatCurrency(item.subtotal) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
