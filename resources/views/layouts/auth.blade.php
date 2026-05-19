<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'PS Billing POS')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100">
        <div class="relative flex min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.22),transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(124,58,237,0.20),transparent_28%),linear-gradient(180deg,#020617_0%,#0f172a_45%,#020617_100%)]"></div>
            <div class="absolute inset-0 opacity-30 [background-image:linear-gradient(rgba(148,163,184,0.08)_1px,transparent_1px),linear-gradient(90deg,rgba(148,163,184,0.08)_1px,transparent_1px)] [background-size:48px_48px]"></div>
            <main class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl flex-col justify-center px-6 py-10 lg:flex-row lg:items-center lg:gap-12">
                <section class="mb-10 max-w-xl lg:mb-0">
                    <div class="inline-flex items-center rounded-full border border-cyan-400/30 bg-cyan-400/10 px-4 py-1 text-sm font-semibold uppercase tracking-[0.3em] text-cyan-200">
                        PS Billing POS
                    </div>
                    <h1 class="mt-6 font-display text-4xl uppercase tracking-[0.12em] text-white sm:text-5xl">
                        Billing Rental PlayStation Modern untuk operasional harian yang cepat.
                    </h1>
                    <p class="mt-5 max-w-lg text-lg text-slate-300">
                        Kelola timer station, POS kasir, dan laporan omzet dalam satu dashboard gelap yang siap dipresentasikan ke owner.
                    </p>
                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-2xl shadow-cyan-950/20 backdrop-blur">
                            <div class="text-sm text-slate-400">Realtime</div>
                            <div class="mt-2 font-display text-2xl text-cyan-300">Timer Station</div>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-2xl shadow-violet-950/20 backdrop-blur">
                            <div class="text-sm text-slate-400">Fast Flow</div>
                            <div class="mt-2 font-display text-2xl text-violet-300">POS Kasir</div>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-2xl shadow-sky-950/20 backdrop-blur">
                            <div class="text-sm text-slate-400">Insight</div>
                            <div class="mt-2 font-display text-2xl text-sky-300">Daily Report</div>
                        </div>
                    </div>
                </section>
                <section class="w-full max-w-md">
                    <div class="rounded-[32px] border border-white/10 bg-slate-900/80 p-8 shadow-[0_40px_120px_-24px_rgba(14,165,233,0.35)] backdrop-blur-xl">
                        @yield('content')
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
