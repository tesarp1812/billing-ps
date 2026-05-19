<script setup>
import { computed, onMounted } from 'vue';
import AppLayout from '../../layouts/AppLayout.vue';
import ProductGrid from '../../components/pos/ProductGrid.vue';
import CartSidebar from '../../components/pos/CartSidebar.vue';
import { usePosStore } from '../../stores/pos';
import { useDashboardStore } from '../../stores/dashboard';
import { useCurrency } from '../../composables/useCurrency';

const posStore = usePosStore();
const dashboardStore = useDashboardStore();
const { formatCurrency } = useCurrency();

const checkoutStations = computed(() =>
    (dashboardStore.stations || []).filter((station) => ['playing', 'paused', 'booking'].includes(station.status))
);

const handleCheckout = async (payload) => {
    await posStore.checkout(payload);
    await Promise.all([
        posStore.fetchProducts(),
        dashboardStore.fetchSummary(),
    ]);
    window.alert('Checkout berhasil diproses.');
};

onMounted(async () => {
    if (!dashboardStore.summary) {
        await dashboardStore.fetchSummary();
    }

    await posStore.fetchProducts();
});
</script>

<template>
    <AppLayout>
        <div class="grid gap-6 xl:grid-cols-[1.4fr,0.8fr]">
            <section>
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-display text-2xl uppercase tracking-[0.12em] text-white">POS Kasir</h3>
                        <p class="mt-1 text-slate-400">Pilih produk dan gabungkan dengan billing station saat checkout.</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
                        Produk aktif: <span class="font-semibold text-white">{{ posStore.products.length }}</span>
                    </div>
                </div>
                <ProductGrid :products="posStore.products" @add="posStore.addToCart" />
            </section>

            <section>
                <CartSidebar
                    :cart="posStore.cart"
                    :total="posStore.cartTotal"
                    :stations="checkoutStations"
                    @update-qty="posStore.updateQty"
                    @remove="posStore.removeFromCart"
                    @checkout="handleCheckout"
                />

                <div class="mt-6 rounded-[28px] border border-white/10 bg-white/5 p-5">
                    <div class="text-sm uppercase tracking-[0.28em] text-slate-400">Quick Summary</div>
                    <div class="mt-4 space-y-3 text-sm text-slate-300">
                        <div class="flex items-center justify-between">
                            <span>Cart total</span>
                            <span class="font-semibold text-white">{{ formatCurrency(posStore.cartTotal) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Station menunggu checkout</span>
                            <span class="font-semibold text-white">{{ checkoutStations.length }}</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
