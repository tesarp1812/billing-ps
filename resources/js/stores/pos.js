import { defineStore } from 'pinia';
import { productService } from '../services/productService';
import { checkoutService } from '../services/checkoutService';

export const usePosStore = defineStore('pos', {
    state: () => ({
        products: [],
        cart: [],
        loading: false,
    }),
    getters: {
        cartTotal: (state) => state.cart.reduce((sum, item) => sum + item.price * item.qty, 0),
    },
    actions: {
        async fetchProducts() {
            this.loading = true;
            try {
                this.products = await productService.list();
            } finally {
                this.loading = false;
            }
        },
        addToCart(product) {
            const existing = this.cart.find((item) => item.id === product.id);

            if (existing) {
                existing.qty += 1;
                return;
            }

            this.cart.push({ ...product, qty: 1 });
        },
        removeFromCart(productId) {
            this.cart = this.cart.filter((item) => item.id !== productId);
        },
        updateQty(productId, qty) {
            const item = this.cart.find((entry) => entry.id === productId);

            if (!item) {
                return;
            }

            item.qty = Math.max(1, qty);
        },
        clearCart() {
            this.cart = [];
        },
        async checkout(payload) {
            const response = await checkoutService.create({
                ...payload,
                items: this.cart.map((item) => ({
                    product_id: item.id,
                    qty: item.qty,
                })),
            });

            this.clearCart();

            return response;
        },
    },
});
