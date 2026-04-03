@extends('layouts.calculator')

@section('content')
<div class="min-h-screen bg-surface">

    {{-- ============================= HERO ============================= --}}
    <section class="relative text-center overflow-hidden" style="padding-top: 130px; padding-bottom: 80px; padding-left: 24px; padding-right: 24px;">
        {{-- Background glow --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-primary/5 rounded-full blur-[140px]"></div>
        </div>

        <div class="relative max-w-3xl mx-auto">
            <span class="inline-block text-[10px] font-bold text-primary uppercase tracking-widest bg-primary/10 px-4 py-1.5 rounded-full mb-6">Proyek Perangkat Lunak Telkom University</span>

            <h1 class="font-headline font-extrabold text-on-surface tracking-tighter leading-snug" style="font-size: clamp(3rem, 8vw, 5.5rem);">
                Built for a<br>
                <span class="solar-gradient">brighter Indonesia</span>
            </h1>

            <p class="text-on-surface-variant text-lg max-w-xl mx-auto leading-relaxed mt-10 mb-14">
                We help you see if solar power is good for you using NASA data
            </p>

            {{-- Stats --}}
            <div class="inline-flex items-center gap-10 bg-surface-container-low border border-outline-variant/10 rounded-2xl px-10 py-6">
                <div>
                    <p class="font-headline font-black text-4xl text-primary">3</p>
                    <p class="text-on-surface-variant text-xs mt-1">Team Members</p>
                </div>
                <div class="w-px h-10 bg-outline-variant/20"></div>
                <div>
                    <p class="font-headline font-black text-4xl text-secondary">1</p>
                    <p class="text-on-surface-variant text-xs mt-1">Semester Project</p>
                </div>
                <div class="w-px h-10 bg-outline-variant/20"></div>
                <div>
                    <p class="font-headline font-black text-4xl text-tertiary">∞</p>
                    <p class="text-on-surface-variant text-xs mt-1">Cups of Coffee</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= TEAM ============================= --}}
    <section class="py-24 px-6 lg:px-20 bg-surface-container-low/40">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <span class="inline-block text-[10px] font-bold text-secondary uppercase tracking-widest bg-secondary/10 px-4 py-1.5 rounded-full mb-4">The People</span>
                <h2 class="font-headline font-extrabold text-on-surface tracking-tight" style="font-size: 2.25rem;">Meet the team</h2>
                <p class="text-on-surface-variant mt-3 max-w-sm mx-auto">Three students from Telkom University who made this project</p>
            </div>

            {{-- 3-column grid --}}
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">

                {{-- Card: Wira --}}
                <div class="group flex flex-col bg-surface-container-low rounded-3xl overflow-hidden border border-outline-variant/10 hover:shadow-2xl hover:shadow-primary/10 hover:-translate-y-2 transition-all duration-300">
                    {{-- Coloured header --}}
                    <div class="flex items-center justify-center bg-gradient-to-br from-primary/25 to-primary/5 relative overflow-hidden" style="height: 200px;">
                        <div class="w-24 h-24 rounded-full bg-primary/20 flex items-center justify-center z-10">
                            <span class="material-symbols-outlined text-primary" style="font-size: 48px; font-variation-settings: 'FILL' 1;">person</span>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-surface-container-low/40 to-transparent"></div>
                        <div class="absolute -top-8 -right-8 w-32 h-32 bg-primary/10 rounded-full blur-2xl"></div>
                    </div>
                    {{-- Body --}}
                    <div class="p-7 text-center flex-1 flex flex-col justify-center">
                        <h3 class="font-headline font-extrabold text-2xl text-on-surface">Wira</h3>
                    </div>
                </div>

                {{-- Card: Khai --}}
                <div class="group flex flex-col bg-surface-container-low rounded-3xl overflow-hidden border border-outline-variant/10 hover:shadow-2xl hover:shadow-secondary/10 hover:-translate-y-2 transition-all duration-300">
                    <div class="flex items-center justify-center bg-gradient-to-br from-secondary/25 to-secondary/5 relative overflow-hidden" style="height: 200px;">
                        <div class="w-24 h-24 rounded-full bg-secondary/20 flex items-center justify-center z-10">
                            <span class="material-symbols-outlined text-secondary" style="font-size: 48px; font-variation-settings: 'FILL' 1;">person</span>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-surface-container-low/40 to-transparent"></div>
                        <div class="absolute -top-8 -right-8 w-32 h-32 bg-secondary/10 rounded-full blur-2xl"></div>
                    </div>
                    <div class="p-7 text-center flex-1 flex flex-col justify-center">
                        <h3 class="font-headline font-extrabold text-2xl text-on-surface">Khai</h3>
                    </div>
                </div>

                {{-- Card: Gia --}}
                <div class="group flex flex-col bg-surface-container-low rounded-3xl overflow-hidden border border-outline-variant/10 hover:shadow-2xl hover:shadow-tertiary/10 hover:-translate-y-2 transition-all duration-300">
                    <div class="flex items-center justify-center bg-gradient-to-br from-tertiary/25 to-tertiary/5 relative overflow-hidden" style="height: 200px;">
                        <div class="w-24 h-24 rounded-full bg-tertiary/20 flex items-center justify-center z-10">
                            <span class="material-symbols-outlined text-tertiary" style="font-size: 48px; font-variation-settings: 'FILL' 1;">person</span>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-surface-container-low/40 to-transparent"></div>
                        <div class="absolute -top-8 -right-8 w-32 h-32 bg-tertiary/10 rounded-full blur-2xl"></div>
                    </div>
                    <div class="p-7 text-center flex-1 flex flex-col justify-center">
                        <h3 class="font-headline font-extrabold text-2xl text-on-surface">Gia</h3>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================= CONTEXT ============================= --}}
    <section class="py-24 px-6 lg:px-20">
        <div class="max-w-5xl mx-auto" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;">

            {{-- Left --}}
            <div>
                <span class="inline-block text-[10px] font-bold text-tertiary uppercase tracking-widest bg-tertiary/10 px-4 py-1.5 rounded-full mb-5">The Context</span>
                <h2 class="font-headline font-extrabold text-on-surface mt-0 tracking-tight leading-tight" style="font-size: 2.25rem;">A real project from a real class</h2>
                <p class="text-on-surface-variant mt-6 text-base leading-relaxed">
                    SolarSmart is our final project for the Software Engineering course at Telkom University
                </p>
                <p class="text-on-surface-variant mt-4 text-base leading-relaxed">
                    We chose solar because it is important for Indonesia and we want to help people use green energy
                </p>
                <div class="flex items-center gap-4 mt-10">
                    <a href="{{ route('calculator') }}" class="solar-gradient text-white px-7 py-3.5 rounded-xl font-headline font-bold text-sm shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">bolt</span>
                        Try the Simulator
                    </a>
                    <a href="{{ route('home') }}" class="text-on-surface-variant hover:text-on-surface text-sm font-bold transition-all flex items-center gap-1.5">
                        Back to Home
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
            </div>

            {{-- Right: Tech Stack --}}
            <div class="bg-surface-container-low rounded-3xl p-8 border border-outline-variant/10">
                <h3 class="font-headline font-extrabold text-xl text-on-surface mb-6">What we built with</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-surface-container rounded-2xl">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary" style="font-size: 20px; font-variation-settings: 'FILL' 1;">code</span>
                        </div>
                        <div>
                            <p class="font-headline font-bold text-on-surface text-sm">Laravel 12 + Livewire</p>
                            <p class="text-on-surface-variant text-xs mt-0.5">Building the website logic and interactive parts</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-surface-container rounded-2xl">
                        <div class="w-10 h-10 bg-secondary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-secondary" style="font-size: 20px; font-variation-settings: 'FILL' 1;">satellite_alt</span>
                        </div>
                        <div>
                            <p class="font-headline font-bold text-on-surface text-sm">NASA POWER API</p>
                            <p class="text-on-surface-variant text-xs mt-0.5">Solar data from NASA satellites</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-surface-container rounded-2xl">
                        <div class="w-10 h-10 bg-tertiary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-tertiary" style="font-size: 20px; font-variation-settings: 'FILL' 1;">map</span>
                        </div>
                        <div>
                            <p class="font-headline font-bold text-on-surface text-sm">Leaflet + OpenStreetMap</p>
                            <p class="text-on-surface-variant text-xs mt-0.5">Interactive map to pick your location</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-surface-container rounded-2xl">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary" style="font-size: 20px; font-variation-settings: 'FILL' 1;">palette</span>
                        </div>
                        <div>
                            <p class="font-headline font-bold text-on-surface text-sm">Tailwind CSS v4</p>
                            <p class="text-on-surface-variant text-xs mt-0.5">Making the design look clean and modern</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>
@endsection
