@extends('layouts.auth')

@section('title', 'Forgot Password | PS Billing POS')

@section('content')
    <h2 class="font-display text-3xl uppercase tracking-[0.12em] text-white">Reset Password</h2>
    <p class="mt-2 text-slate-400">Masukkan email untuk mengirim link reset password.</p>

    @if ($status)
        <div class="mt-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
            {{ $status }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-300">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required class="block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white focus:border-cyan-400 focus:ring-cyan-400">
            @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-cyan-400 via-sky-500 to-violet-500 px-4 py-3 font-semibold text-slate-950">
            Kirim Link Reset
        </button>
    </form>
@endsection
