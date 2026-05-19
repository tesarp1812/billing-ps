import { api } from './api';

export const reportService = {
    async daily(date) {
        const { data } = await api.get('/api/reports/daily', { params: { date } });
        return data;
    },
};
