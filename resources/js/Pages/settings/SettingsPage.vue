<script setup>
import AppLayout from '../../layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <div class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-5 xl:col-span-2">
                <h3 class="font-display text-2xl uppercase tracking-[0.12em] text-white">System Settings</h3>
                <p class="mt-2 max-w-2xl text-slate-400">
                    Halaman ini disiapkan untuk fase berikutnya: pengaturan harga per cabang, metode pembayaran, printer, dan branding SaaS.
                </p>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                        <div class="text-sm uppercase tracking-[0.28em] text-slate-400">Business</div>
                        <div class="mt-2 text-xl font-semibold text-white">PS Billing POS</div>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                        <div class="text-sm uppercase tracking-[0.28em] text-slate-400">Mode</div>
                        <div class="mt-2 text-xl font-semibold text-white">Single Branch MVP</div>
                    </div>
                </div>
            </div>
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-5">
                <div class="text-sm uppercase tracking-[0.28em] text-slate-400">Roadmap</div>
                <ul class="mt-4 space-y-3 text-sm text-slate-300">
                    <li>Multi cabang dan tenant isolation</li>
                    <li>Printer receipt dan drawer integration</li>
                    <li>Promo, membership, dan booking advance</li>
                    <li>Realtime websocket per station</li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>
