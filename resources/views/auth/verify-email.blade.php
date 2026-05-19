@extends('layouts.auth')

@section('title', 'Verify Email | PS Billing POS')

@section('content')
    <h2 class="font-display text-3xl uppercase tracking-[0.12em] text-white">Verifikasi Email</h2>
    <p class="mt-2 text-slate-400">
        Sebelum melanjutkan, cek email Anda untuk tautan verifikasi. Jika belum menerima email, kirim ulang dari sini.
    </p>

    @if ($status === 'verification-link-sent')
        <div class="mt-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
            Link verifikasi baru sudah dikirim.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mt-8">
        @csrf
        <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-cyan-400 via-sky-500 to-violet-500 px-4 py-3 font-semibold text-slate-950">
            Kirim Ulang Link Verifikasi
        </button>
    </form>
@endsection
