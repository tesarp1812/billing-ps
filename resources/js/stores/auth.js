import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: window.PSBILLING_BOOTSTRAP?.user || null,
    }),
});
