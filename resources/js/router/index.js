import { createRouter, createWebHistory } from 'vue-router';
import DashboardPage from '../pages/dashboard/DashboardPage.vue';
import BillingPage from '../pages/billing/BillingPage.vue';
import PosPage from '../pages/pos/PosPage.vue';
import TransactionsPage from '../pages/transactions/TransactionsPage.vue';
import ReportsPage from '../pages/reports/ReportsPage.vue';
import SettingsPage from '../pages/settings/SettingsPage.vue';
import LoginPage from '../pages/auth/Login.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', redirect: '/dashboard' },
        { path: '/dashboard', name: 'dashboard', component: DashboardPage },
        { path: '/login', name: 'login', component: LoginPage },
        { path: '/billing', name: 'billing', component: BillingPage },
        { path: '/pos', name: 'pos', component: PosPage },
        { path: '/transactions', name: 'transactions', component: TransactionsPage },
        { path: '/reports', name: 'reports', component: ReportsPage },
        { path: '/settings', name: 'settings', component: SettingsPage },
    ],
});

export default router;
