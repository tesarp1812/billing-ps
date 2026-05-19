@extends('layouts.auth')

@section('title', 'Set New Password | PS Billing POS')

@section('content')
    <h2 class="font-display text-3xl uppercase tracking-[0.12em] text-white">Password Baru</h2>
    <p class="mt-2 text-slate-400">Masukkan password baru untuk akun Anda.</p>

    <form method="POST" action="{{ route('password.update') }}" class="mt-8 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-300">Email</label>
            <input name="email" type="email" value="{{ old('email', $email) }}" required class="block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white focus:border-cyan-400 focus:ring-cyan-400">
            @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-300">Password</label>
            <input name="password" type="password" required class="block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white focus:border-cyan-400 focus:ring-cyan-400">
            @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-300">Konfirmasi Password</label>
            <input name="password_confirmation" type="password" required class="block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white focus:border-cyan-400 focus:ring-cyan-400">
        </div>
        <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-cyan-400 via-sky-500 to-violet-500 px-4 py-3 font-semibold text-slate-950">
            Update Password
        </button>
    </form>
@endsection
