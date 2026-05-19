@extends('layouts.auth')

@section('title', 'Login | PS Billing POS')

@section('content')
    <div>
        <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Welcome Back</p>
        <h2 class="mt-3 font-display text-3xl uppercase tracking-[0.12em] text-white">Login Operator</h2>
        <p class="mt-2 text-slate-400">Masuk untuk mulai mengelola billing station dan transaksi kasir.</p>
    </div>

    @if ($status)
        <div class="mt-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
            {{ $status }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-300">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required autofocus class="block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white placeholder-slate-500 focus:border-cyan-400 focus:ring-cyan-400">
            @error('email')
                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-300">Password</label>
            <input name="password" type="password" required class="block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white placeholder-slate-500 focus:border-cyan-400 focus:ring-cyan-400">
            @error('password')
                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
            @enderror
        </div>
        <label class="flex items-center gap-3 text-sm text-slate-400">
            <input type="checkbox" name="remember" class="rounded border-white/20 bg-slate-900 text-cyan-400 focus:ring-cyan-400">
            Ingat sesi login
        </label>
        <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-cyan-400 via-sky-500 to-violet-500 px-4 py-3 font-semibold text-slate-950 shadow-lg shadow-cyan-500/30 transition hover:scale-[1.01]">
            Masuk ke Dashboard
        </button>
    </form>

    <div class="mt-6 flex items-center justify-between text-sm text-slate-400">
        @if ($canResetPassword)
            <a href="{{ route('password.request') }}" class="transition hover:text-cyan-300">Lupa password?</a>
        @endif
        <a href="{{ route('register') }}" class="transition hover:text-cyan-300">Buat akun</a>
    </div>

    <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
        Demo account:
        <div class="mt-2 space-y-1 text-slate-400">
            <div><span class="text-cyan-300">admin@test.com</span> / password</div>
            <div><span class="text-cyan-300">cashier@test.com</span> / password</div>
        </div>
    </div>
@endsection
