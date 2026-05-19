import { api } from './api';

export const productService = {
    async list(params = {}) {
        const { data } = await api.get('/api/products', { params });
        return data;
    },
};
