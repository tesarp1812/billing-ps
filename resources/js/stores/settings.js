import { defineStore } from 'pinia';
import { settingsService } from '../services/settingsService';

export const useSettingsStore = defineStore('settings', {
    state: () => ({
        settings: {},
        loading: false,
        saving: false,
        errors: null,
    }),

    getters: {
        businessName: (state) => state.settings.business_name || 'PS Billing POS',
        businessAddress: (state) => state.settings.business_address || '',
        businessWhatsapp: (state) => state.settings.business_whatsapp || '',
        businessLogo: (state) => state.settings.business_logo || null,
        
        themeColor: (state) => state.settings.theme_color || '#06b6d4',
        themeMode: (state) => state.settings.theme_mode || 'dark',
        
        priceRegular: (state) => Number(state.settings.price_regular) || 10000,
        priceVip: (state) => Number(state.settings.price_vip) || 15000,
        gracePeriod: (state) => Number(state.settings.grace_period) || 5,
        autoRound: (state) => state.settings.auto_round !== false,
        minimumCharge: (state) => Number(state.settings.minimum_charge) || 5000,
        
        openTime: (state) => state.settings.open_time || '10:00',
        closeTime: (state) => state.settings.close_time || '23:00',
        idleLogoutMinutes: (state) => Number(state.settings.idle_logout_minutes) || 30,
        defaultPrinter: (state) => state.settings.default_printer || '',
        
        timezone: (state) => state.settings.timezone || 'Asia/Jakarta',
        currency: (state) => state.settings.currency || 'IDR',
        taxPercent: (state) => Number(state.settings.tax_percent) || 0,
        serviceChargePercent: (state) => Number(state.settings.service_charge_percent) || 0,
    },

    actions: {
        async fetchSettings() {
            this.loading = true;
            this.errors = null;
            try {
                this.settings = await settingsService.get();
            } catch (error) {
                this.errors = error.response?.data?.errors || { message: 'Gagal memuat pengaturan' };
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async saveSettings(settings) {
            this.saving = true;
            this.errors = null;
            try {
                const response = await settingsService.update(settings);
                this.settings = response.settings;
                return response;
            } catch (error) {
                this.errors = error.response?.data?.errors || { message: 'Gagal menyimpan pengaturan' };
                throw error;
            } finally {
                this.saving = false;
            }
        },

        async resetSettings() {
            this.saving = true;
            this.errors = null;
            try {
                const response = await settingsService.reset();
                this.settings = response.settings;
                return response;
            } catch (error) {
                this.errors = error.response?.data?.errors || { message: 'Gagal reset pengaturan' };
                throw error;
            } finally {
                this.saving = false;
            }
        },
    },
});