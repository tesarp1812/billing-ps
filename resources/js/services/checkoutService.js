import { api } from './api';

export const checkoutService = {
    async create(payload) {
        const { data } = await api.post('/api/checkout', payload);
        return data;
    },
};
