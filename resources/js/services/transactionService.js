import { api } from './api';

export const transactionService = {
    async list() {
        const { data } = await api.get('/api/transactions');
        return data;
    },
};
