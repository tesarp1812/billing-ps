<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const email = ref('')
const password = ref('')
const remember = ref(false)

const loading = ref(false)
const error = ref('')
const success = ref('')

const submit = async () => {
    loading.value = true
    error.value = ''
    success.value = ''

    try {
        // Laravel CSRF
        await axios.get('/sanctum/csrf-cookie')

        // Login Laravel session
        await axios.post('/login', {
            email: email.value,
            password: password.value,
            remember: remember.value,
        })

        success.value = 'Login berhasil'

        setTimeout(() => {
            router.push('/dashboard')
        }, 500)
    } catch (err) {
        error.value =
            err.response?.data?.message ||
            'Email atau password salah'
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div
        class="min-h-screen flex items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.14),transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(124,58,237,0.14),transparent_24%),linear-gradient(180deg,#020617_0%,#0f172a_50%,#020617_100%)] px-4"
    >
        <div
            class="w-full max-w-md rounded-[28px] border border-white/10 bg-slate-900/70 p-8 shadow-2xl backdrop-blur"
        >
            <div class="mb-8 text-center">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">
                    Control Room
                </p>

                <h1
                    class="mt-3 text-4xl font-bold uppercase tracking-[0.14em] text-white"
                >
                    PS Billing
                </h1>

                <p class="mt-3 text-slate-400">
                    Login ke sistem billing dan kasir
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="mb-2 block text-sm text-slate-300">
                        Email
                    </label>

                    <input
                        v-model="email"
                        type="email"
                        required
                        autocomplete="username"
                        class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none transition focus:border-cyan-400"
                        placeholder="admin@email.com"
                    />
                </div>

                <div>
                    <label class="mb-2 block text-sm text-slate-300">
                        Password
                    </label>

                    <input
                        v-model="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none transition focus:border-cyan-400"
                        placeholder="••••••••"
                    />
                </div>

                <label class="flex items-center gap-3 text-sm text-slate-400">
                    <input
                        v-model="remember"
                        type="checkbox"
                        class="h-4 w-4 rounded border-white/10 bg-white/5"
                    />
                    Remember me
                </label>

                <div
                    v-if="error"
                    class="rounded-2xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-200"
                >
                    {{ error }}
                </div>

                <div
                    v-if="success"
                    class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200"
                >
                    {{ success }}
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full rounded-2xl bg-cyan-500 px-4 py-3 font-semibold text-slate-950 transition hover:bg-cyan-400 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    {{ loading ? 'Loading...' : 'Log in' }}
                </button>
            </form>
        </div>
    </div>
</template>