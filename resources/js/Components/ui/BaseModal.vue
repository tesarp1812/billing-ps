<script setup>
defineProps({
    open: Boolean,
    title: String,
});

const emit = defineEmits(['close']);
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/75 p-4 backdrop-blur-sm">
        <div class="w-full max-w-2xl rounded-[32px] border border-white/10 bg-slate-900 p-6 shadow-[0_40px_120px_-24px_rgba(14,165,233,0.35)]">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="font-display text-2xl uppercase tracking-[0.14em] text-white">{{ title }}</h3>
                <button class="rounded-full border border-white/10 px-3 py-1 text-sm text-slate-400 hover:text-white" @click="emit('close')">Close</button>
            </div>
            <slot />
        </div>
    </div>
</template>
