<script setup>
defineProps({
    label: String,
    value: [String, Number],
    hint: String,
});
</script>

<template>
    <div class="rounded-[28px] border border-white/10 bg-white/5 p-5 shadow-lg shadow-slate-950/20">
        <p class="text-sm uppercase tracking-[0.28em] text-slate-400">{{ label }}</p>
        <p class="mt-4 font-display text-3xl uppercase tracking-[0.08em] text-white">{{ value }}</p>
        <p class="mt-2 text-sm text-slate-500">{{ hint }}</p>
    </div>
</template>
