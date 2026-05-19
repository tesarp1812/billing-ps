import { api } from './api';

export const stationService = {
    async list() {
        const { data } = await api.get('/api/stations');
        return data;
    },
    async start(stationId, payload) {
        const { data } = await api.post(`/api/stations/${stationId}/start`, payload);
        return data;
    },
    async pause(stationId) {
        const { data } = await api.post(`/api/stations/${stationId}/pause`);
        return data;
    },
    async stop(stationId) {
        const { data } = await api.post(`/api/stations/${stationId}/stop`);
        return data;
    },
    async addTime(stationId, minutes) {
        const { data } = await api.post(`/api/stations/${stationId}/add-time`, { minutes });
        return data;
    },
};
