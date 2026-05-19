import { api } from './api';

export const settingsService = {
    async get() {
        const { data } = await api.get('/api/settings');
        return data.settings;
    },

    async update(settings) {
        const { data } = await api.put('/api/settings', settings);
        return data;
    },

    async reset() {
        const { data } = await api.post('/api/settings/reset');
        return data;
    },
};