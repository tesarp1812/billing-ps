import { defineStore } from 'pinia';
import { dashboardService } from '../services/dashboardService';
import { stationService } from '../services/stationService';

export const useDashboardStore = defineStore('dashboard', {
    state: () => ({
        summary: null,
        loading: false,
    }),
    getters: {
        stations: (state) => state.summary?.stations || [],
    },
    actions: {
        async fetchSummary() {
            this.loading = true;
            try {
                this.summary = await dashboardService.summary();
            } finally {
                this.loading = false;
            }
        },
        async refreshStations() {
            const stations = await stationService.list();
            this.summary = {
                ...(this.summary || {}),
                stations,
            };
        },
    },
});
