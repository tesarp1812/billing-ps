@extends('layouts.auth')

@section('title', 'Confirm Password | PS Billing POS')

@section('content')
    <h2 class="font-display text-3xl uppercase tracking-[0.12em] text-white">Konfirmasi Password</h2>
    <p class="mt-2 text-slate-400">Masukkan password Anda untuk melanjutkan aksi sensitif.</p>

    <form method="POST" action="{{ url('/confirm-password') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-300">Password</label>
            <input name="password" type="password" required class="block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white focus:border-cyan-400 focus:ring-cyan-400">
            @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-cyan-400 via-sky-500 to-violet-500 px-4 py-3 font-semibold text-slate-950">
            Konfirmasi
        </button>
    </form>
@endsection
