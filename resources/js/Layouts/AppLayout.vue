<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const route = useRoute();
const authStore = useAuthStore();
const csrfToken = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '';

const navigation = [
    { name: 'Dashboard', path: '/dashboard' },
    { name: 'Billing', path: '/billing' },
    { name: 'POS Kasir', path: '/pos' },
    { name: 'Transaksi', path: '/transactions' },
    { name: 'Reports', path: '/reports' },
    { name: 'Settings', path: '/settings' },
];

const currentTitle = computed(() => navigation.find((item) => item.path === route.path)?.name || 'PS Billing');
</script>

<template>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.14),transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(124,58,237,0.14),transparent_24%),linear-gradient(180deg,#020617_0%,#0f172a_50%,#020617_100%)] text-slate-100">
        <div class="mx-auto flex min-h-screen max-w-[1700px] gap-6 px-4 py-4 lg:px-6">
            <aside class="hidden w-72 shrink-0 rounded-[28px] border border-white/10 bg-slate-900/80 p-6 shadow-2xl shadow-cyan-950/20 backdrop-blur lg:block">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Control Room</p>
                    <h1 class="mt-3 font-display text-3xl uppercase tracking-[0.14em] text-white">PS Billing</h1>
                </div>

                <nav class="mt-10 space-y-2">
                    <RouterLink
                        v-for="item in navigation"
                        :key="item.path"
                        :to="item.path"
                        class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold transition"
                        :class="route.path === item.path ? 'bg-gradient-to-r from-cyan-400/20 to-violet-500/20 text-white ring-1 ring-cyan-400/30' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                    >
                        <span>{{ item.name }}</span>
                    </RouterLink>
                </nav>

                <div class="mt-10 rounded-3xl border border-white/10 bg-white/5 p-4">
                    <div class="text-sm text-slate-400">Login as</div>
                    <div class="mt-1 text-lg font-semibold text-white">{{ authStore.user?.name }}</div>
                    <div class="text-sm text-slate-500">{{ authStore.user?.email }}</div>
                </div>

                <form method="POST" action="/logout" class="mt-4">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit" class="w-full rounded-2xl border border-white/10 px-4 py-3 text-sm font-semibold text-slate-300 transition hover:border-rose-400/30 hover:bg-rose-400/10 hover:text-rose-200">
                        Logout
                    </button>
                </form>
            </aside>

            <main class="min-w-0 flex-1 rounded-[28px] border border-white/10 bg-slate-900/60 p-4 shadow-2xl shadow-violet-950/10 backdrop-blur md:p-6">
                <header class="mb-6 flex flex-col gap-4 rounded-[28px] border border-white/10 bg-white/5 p-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.32em] text-slate-400">MVP Demo</p>
                        <h2 class="mt-2 font-display text-3xl uppercase tracking-[0.14em] text-white">{{ currentTitle }}</h2>
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl border border-cyan-400/20 bg-cyan-400/10 px-4 py-3 text-sm text-cyan-100">
                        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400 shadow-[0_0_18px_rgba(74,222,128,0.9)]"></span>
                        System ready for cashier operation
                    </div>
                </header>

                <nav class="mb-6 flex gap-3 overflow-x-auto rounded-[28px] border border-white/10 bg-white/5 p-3 lg:hidden">
                    <RouterLink
                        v-for="item in navigation"
                        :key="item.path"
                        :to="item.path"
                        class="whitespace-nowrap rounded-2xl px-4 py-2 text-sm font-semibold transition"
                        :class="route.path === item.path ? 'bg-gradient-to-r from-cyan-400/20 to-violet-500/20 text-white ring-1 ring-cyan-400/30' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                    >
                        {{ item.name }}
                    </RouterLink>
                </nav>

                <slot />
            </main>
        </div>
    </div>
</template>
