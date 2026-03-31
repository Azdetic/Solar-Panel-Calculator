@extends('layouts.calculator')

@section('content')
<div class="min-h-screen">

    {{-- Hero --}}
    <section class="relative flex flex-col items-center justify-center h-screen px-6 lg:px-12 overflow-hidden">
        {{-- Background Glow --}}
        <div class="absolute top-0 left-1/4 -translate-x-1/2 w-[40vw] h-[40vw] bg-primary-fixed-dim/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center w-full max-w-[1500px] mx-auto z-10">
            {{-- Left: Content --}}
            <div class="text-left animate-fade-in-left">
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <div class="inline-flex items-center gap-2 bg-primary/10 border border-primary/20 text-primary px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">wb_sunny</span>
                    NASA POWER
                </div>
                <div class="inline-flex items-center gap-2 bg-secondary/10 border border-secondary/20 text-secondary px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">map</span>
                    OpenStreetMap
                </div>
            </div>

                <h1 class="font-headline font-extrabold text-on-surface leading-[1.1] tracking-tighter text-6xl sm:text-7xl lg:text-8xl mb-6">
                    Should you go solar?
                    <br>
                    <span class="solar-gradient">Let's check</span>
                </h1>

                <p class="text-on-surface-variant text-lg lg:text-xl max-w-xl leading-relaxed mb-10">
                    Pick your location, enter your roof size and budget. We fetch real sunlight data from NASA and run the numbers for you
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('calculator') }}"
                       class="solar-gradient text-white px-10 py-5 rounded-2xl font-headline font-extrabold text-lg shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">bolt</span>
                        Try the Simulator
                    </a>
                    <a href="#steps"
                       class="bg-surface-container-high text-on-surface px-10 py-5 rounded-2xl font-headline font-bold text-lg hover:bg-surface-container-highest transition-all">
                        See how it works
                    </a>
                </div>
            </div>

            {{-- Right: Image --}}
            <div class="hidden lg:block relative animate-fade-in-right">
                <div class="absolute -inset-4 bg-primary/10 blur-2xl rounded-3xl -z-10"></div>
                <div class="rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/10 aspect-square lg:aspect-video">
                    <img src="{{ asset('images/homepagephoto.jpg') }}" alt="Premium solar panels" class="w-full h-full object-cover">
                </div>
                {{-- Floating Stat or Badge --}}
                <div class="absolute -bottom-6 -left-6 bg-surface-container-low border border-outline-variant/20 p-4 rounded-2xl shadow-xl animate-bounce-subtle">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">solar_power</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-primary font-bold uppercase tracking-widest">Global Data</p>
                            <p class="text-on-surface font-headline font-bold text-sm">Indonesia Ready</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Numbers --}}
    <section class="px-8 lg:px-20 py-14 border-y border-outline-variant/10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-10 max-w-4xl mx-auto text-center">
            <div>
                <p class="text-4xl font-headline font-extrabold text-primary">5.5+</p>
                <p class="text-outline text-sm mt-1">kWh/m² per day in Indonesia</p>
            </div>
            <div>
                <p class="text-4xl font-headline font-extrabold text-secondary">25 yrs</p>
                <p class="text-outline text-sm mt-1">solar panel lifespan</p>
            </div>
            <div>
                <p class="text-4xl font-headline font-extrabold text-tertiary">~30%</p>
                <p class="text-outline text-sm mt-1">electricity bill reduction</p>
            </div>
            <div>
                <p class="text-4xl font-headline font-extrabold text-on-surface">NASA</p>
                <p class="text-outline text-sm mt-1">satellite solar data source</p>
            </div>
        </div>
    </section>

    {{-- Steps --}}
    <section id="steps" class="px-8 lg:px-20 py-20">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <span class="text-[10px] font-bold text-primary uppercase tracking-widest">how it works</span>
                <h2 class="font-headline text-4xl font-extrabold text-on-surface mt-2 tracking-tight">Three steps, done in 30 seconds</h2>
                <p class="text-on-surface-variant mt-3 text-base max-w-md mx-auto">No solar knowledge needed. Fill in a few numbers and the app does the rest</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-surface-container-low rounded-2xl p-7 border border-outline-variant/20 hover:-translate-y-1 hover:border-primary/30 transition-all shadow-sm">
                    <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">pin_drop</span>
                    </div>
                    <p class="text-[10px] font-bold text-outline-variant uppercase tracking-widest mb-1">Step 1</p>
                    <h3 class="font-headline text-xl font-bold text-on-surface mb-2">Drop a pin</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Drag the map to your building or type an address. Works anywhere in Indonesia</p>
                </div>

                <div class="bg-surface-container-low rounded-2xl p-7 border border-outline-variant/20 hover:-translate-y-1 hover:border-secondary/30 transition-all shadow-sm">
                    <div class="w-12 h-12 bg-secondary/10 rounded-xl flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-secondary text-2xl" style="font-variation-settings: 'FILL' 1;">tune</span>
                    </div>
                    <p class="text-[10px] font-bold text-outline-variant uppercase tracking-widest mb-1">Step 2</p>
                    <h3 class="font-headline text-xl font-bold text-on-surface mb-2">Fill in 3 things</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Roof size, your monthly PLN bill, and your budget. That's all the app needs</p>
                </div>

                <div class="bg-surface-container-low rounded-2xl p-7 border border-outline-variant/20 hover:-translate-y-1 hover:border-tertiary/30 transition-all shadow-sm">
                    <div class="w-12 h-12 bg-tertiary/10 rounded-xl flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-tertiary text-2xl" style="font-variation-settings: 'FILL' 1;">insights</span>
                    </div>
                    <p class="text-[10px] font-bold text-outline-variant uppercase tracking-widest mb-1">Step 3</p>
                    <h3 class="font-headline text-xl font-bold text-on-surface mb-2">Read your report</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">System size, monthly savings, payback period, and how much CO₂ you'd avoid per year</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Why accurate --}}
    <section class="px-8 lg:px-20 py-20 bg-surface-container-lowest/60">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <span class="text-[10px] font-bold text-secondary uppercase tracking-widest">why trust the numbers</span>
                <h2 class="font-headline text-4xl font-extrabold text-on-surface mt-2 tracking-tight">Where the data comes from</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex items-start gap-5 bg-surface-container-low p-6 rounded-2xl border border-outline-variant/20 hover:border-primary/20 transition-all shadow-sm">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">satellite_alt</span>
                    </div>
                    <div>
                        <h4 class="font-headline font-bold text-on-surface">NASA POWER API</h4>
                        <p class="text-on-surface-variant text-sm mt-1 leading-relaxed">Solar radiation data comes from NASA's satellite archive, specific to your coordinates. Not a national average, your actual location</p>
                    </div>
                </div>

                <div class="flex items-start gap-5 bg-surface-container-low p-6 rounded-2xl border border-outline-variant/20 hover:border-secondary/20 transition-all shadow-sm">
                    <div class="w-10 h-10 bg-secondary/10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">currency_exchange</span>
                    </div>
                    <div>
                        <h4 class="font-headline font-bold text-on-surface">Real PLN tariff rates</h4>
                        <p class="text-on-surface-variant text-sm mt-1 leading-relaxed">Savings are calculated using your actual PLN tariff tier, so the rupiah numbers reflect what you'd actually save on your bill</p>
                    </div>
                </div>

                <div class="flex items-start gap-5 bg-surface-container-low p-6 rounded-2xl border border-outline-variant/20 hover:border-tertiary/20 transition-all shadow-sm">
                    <div class="w-10 h-10 bg-tertiary/10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-tertiary" style="font-variation-settings: 'FILL' 1;">map</span>
                    </div>
                    <div>
                        <h4 class="font-headline font-bold text-on-surface">Any location in Indonesia</h4>
                        <p class="text-on-surface-variant text-sm mt-1 leading-relaxed">Interactive map with drag markers and address search. Sabang to Merauke, it works</p>
                    </div>
                </div>

                <div class="flex items-start gap-5 bg-surface-container-low p-6 rounded-2xl border border-outline-variant/20 hover:border-outline/20 transition-all shadow-sm">
                    <div class="w-10 h-10 bg-surface-container-highest rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-on-surface-variant" style="font-variation-settings: 'FILL' 1;">eco</span>
                    </div>
                    <div>
                        <h4 class="font-headline font-bold text-on-surface">CO₂ numbers too</h4>
                        <p class="text-on-surface-variant text-sm mt-1 leading-relaxed">Every result shows how many kg of CO₂ your panels would avoid each year, based on Indonesia's grid emission factor</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Glossary --}}
    <section class="px-8 lg:px-20 py-20">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-widest">glossary</span>
                <h2 class="font-headline text-4xl font-extrabold text-on-surface mt-2 tracking-tight">What do all these terms mean?</h2>
                <p class="text-on-surface-variant mt-3 text-base max-w-md mx-auto">The report uses a few technical terms. Here's what they actually mean</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/20 hover:border-primary/20 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-primary/10 text-primary font-headline font-extrabold text-sm px-3 py-1 rounded-lg">kWp</span>
                        <span class="text-on-surface-variant text-xs">kilowatt-peak</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">The size of your solar system. 1 kWp means the panels produce 1 kW at peak sunlight. Bigger number, more electricity. A typical Indonesian home runs on 2 to 4 kWp</p>
                </div>

                <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/20 hover:border-secondary/20 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-secondary/10 text-secondary font-headline font-extrabold text-sm px-3 py-1 rounded-lg">GHI</span>
                        <span class="text-on-surface-variant text-xs">Global Horizontal Irradiance</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">How much sunlight hits a flat surface at your location per day, in kWh/m². Higher GHI means more power generated. Most of Indonesia is between 4.5 and 5.5</p>
                </div>

                <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/20 hover:border-tertiary/20 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-tertiary/10 text-tertiary font-headline font-extrabold text-sm px-3 py-1 rounded-lg">kWh</span>
                        <span class="text-on-surface-variant text-xs">kilowatt-hour</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">The unit on your PLN bill. Running a 1000W AC for 1 hour uses 1 kWh. Every kWh your panels produce is power you don't pay PLN for</p>
                </div>

                <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/20 hover:border-outline/20 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-surface-container-highest font-headline font-extrabold text-sm px-3 py-1 rounded-lg text-on-surface">Payback Period</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">How many years until your savings cover the installation cost. After that, the electricity is basically free for the remaining years of the panel's 25-year life</p>
                </div>

                <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/20 hover:border-primary/20 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-primary/10 text-primary font-headline font-extrabold text-sm px-3 py-1 rounded-lg">Energy Independence</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">What percentage of your monthly bill your panels can cover. 25% means one quarter covered by solar, the rest still from PLN. Bigger roof or budget pushes this up</p>
                </div>

                <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/20 hover:border-secondary/20 transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-secondary/10 text-secondary font-headline font-extrabold text-sm px-3 py-1 rounded-lg">CO₂ Reduction</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Each kWh from PLN's grid emits ~0.785 kg of CO₂. Solar replaces those kWh, so this number shows the emissions you'd cut each year</p>
                </div>

            </div>

            <div class="mt-8 bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/10 text-center">
                <p class="text-on-surface-variant text-sm leading-relaxed max-w-2xl mx-auto">
                    Numbers are based on NASA POWER satellite data, 20% panel efficiency, 25% system loss, and Rp 11,000,000/kWp market price. These are feasibility estimates, not engineering quotes
                </p>
            </div>
        </div>
    </section>

    {{-- Bottom CTA --}}
    <section class="px-8 lg:px-20 py-24">
        <div class="max-w-2xl mx-auto text-center">
            <span class="material-symbols-outlined text-5xl text-primary mb-6 block" style="font-variation-settings: 'FILL' 1;">solar_power</span>
            <h2 class="font-headline text-4xl font-extrabold text-on-surface tracking-tight">Ready to check your roof?</h2>
            <p class="text-on-surface-variant mt-4 mb-8 text-lg">Takes about 30 seconds. No account needed</p>
            <a href="{{ route('calculator') }}"
               class="solar-gradient text-white px-10 py-4 rounded-xl font-headline font-extrabold text-lg shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-xl">bolt</span>
                Start the Simulation
            </a>
        </div>
    </section>

</div>
@endsection
