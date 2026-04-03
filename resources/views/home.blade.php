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

    {{-- Glossary Section --}}
    <section class="px-8 lg:px-20 py-24">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[10px] font-bold text-primary uppercase tracking-widest bg-primary/10 px-3 py-1 rounded-full">Knowledge Hub</span>
                <h2 class="font-headline text-4xl font-extrabold text-on-surface mt-4 tracking-tight leading-loose">Understanding the numbers</h2>
                <p class="text-on-surface-variant mt-3 text-base max-w-lg mx-auto">Solar tech can be confusing. We've simplified the key terms so you know exactly what your report means.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-surface-container-low rounded-3xl p-7 border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 transition-all group">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">bolt</span>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-headline font-bold text-on-surface">kWp</h3>
                        <span class="text-[10px] text-on-surface-variant/60 font-medium uppercase tracking-tighter">kilowatt-peak</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">The size of your solar system. 1 kWp means the panels produce 1 kW at peak sunlight. A typical home needs 2 to 4 kWp.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-surface-container-low rounded-3xl p-7 border border-outline-variant/10 hover:shadow-xl hover:shadow-secondary/5 hover:-translate-y-1 transition-all group">
                    <div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-2xl" style="font-variation-settings: 'FILL' 1;">wb_sunny</span>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-headline font-bold text-on-surface">GHI</h3>
                        <span class="text-[10px] text-on-surface-variant/60 font-medium uppercase tracking-tighter">Solar Radiation</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">How much sunlight energy hits your roof. Most of Indonesia is between 4.5 and 5.5 kWh/m² per day.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-surface-container-low rounded-3xl p-7 border border-outline-variant/10 hover:shadow-xl hover:shadow-tertiary/5 hover:-translate-y-1 transition-all group">
                    <div class="w-12 h-12 bg-tertiary/10 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-tertiary text-2xl" style="font-variation-settings: 'FILL' 1;">electric_bolt</span>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-headline font-bold text-on-surface">kWh</h3>
                        <span class="text-[10px] text-on-surface-variant/60 font-medium uppercase tracking-tighter">Energy Unit</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">The unit on your PLN bill. Every kWh your panels generate is one less unit you pay for on your monthly electricity bill.</p>
                </div>

                <!-- Card 4 -->
                <div class="bg-surface-container-low rounded-3xl p-7 border border-outline-variant/10 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 transition-all group">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">currency_exchange</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2">Payback Period</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">How many years until your savings cover the installation cost. After that, your electricity is basically free.</p>
                </div>

                <!-- Card 5 -->
                <div class="bg-surface-container-low rounded-3xl p-7 border border-outline-variant/10 hover:shadow-xl hover:shadow-secondary/5 hover:-translate-y-1 transition-all group">
                    <div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-2xl" style="font-variation-settings: 'FILL' 1;">home</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2">Independence</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">What percentage of your monthly bill your panels can cover. Higher numbers mean less reliance on the grid.</p>
                </div>

                <!-- Card 6 -->
                <div class="bg-surface-container-low rounded-3xl p-7 border border-outline-variant/10 hover:shadow-xl hover:shadow-tertiary/5 hover:-translate-y-1 transition-all group">
                    <div class="w-12 h-12 bg-tertiary/10 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-tertiary text-2xl" style="font-variation-settings: 'FILL' 1;">eco</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2">CO₂ Reduction</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">The estimated weight of carbon emissions you avoid each year by switching to clean solar power.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Methodology Section --}}
    <section class="px-8 lg:px-20 py-24 bg-surface-container-low">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <div>
                <span class="text-[10px] font-bold text-primary uppercase tracking-widest bg-primary/10 px-3 py-1 rounded-full leading-none">Transparency</span>
                <h2 class="font-headline text-4xl font-extrabold text-on-surface mt-5 tracking-tight leading-tight">The math behind the magic</h2>
                <p class="text-on-surface-variant mt-5 text-base leading-relaxed">
                    We combine real-time satellite climatology with local market benchmarks to deliver results that are both accurate and realistic for the Indonesian landscape.
                </p>
                
                <div class="space-y-8 mt-10">
                    <div class="flex gap-5">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0 font-headline font-black text-primary">1</div>
                        <div>
                            <h4 class="font-headline font-bold text-on-surface">Market Pricing </h4>
                            <p class="text-on-surface-variant text-sm mt-1 leading-relaxed">
                                Curated benchmarks from <a href="https://iesr.or.id/en/" target="_blank" class="text-primary font-bold hover:underline">IESR</a> for premium residential grid-tie installations in Indonesia (Avg. Rp 11jt/kWp).
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0 font-headline font-black text-primary">2</div>
                        <div>
                            <h4 class="font-headline font-bold text-on-surface">NASA POWER Climatology</h4>
                            <p class="text-on-surface-variant text-sm mt-1 leading-relaxed">
                                We fetch multi-year solar irradiance averages (GHI) directly for your coordinates using NASA's global earth observation satellites.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0 font-headline font-black text-primary">3</div>
                        <div>
                            <h4 class="font-headline font-bold text-on-surface">Precision Payback Logic</h4>
                            <p class="text-on-surface-variant text-sm mt-1 leading-relaxed">
                                A simple, honest ratio of total investment divided by your verified PLN savings over a 25-year system lifespan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container rounded-[2rem] p-10 border border-outline-variant/10 shadow-3xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 blur-3xl rounded-full -mr-32 -mt-32"></div>
                
                <h3 class="font-headline font-extrabold text-2xl text-on-surface mb-8">Simulation Example</h3>
                <div class="space-y-6">
                    <div class="flex justify-between items-center py-4 border-b border-outline-variant/10">
                        <span class="text-on-surface-variant font-medium text-sm">Standard System (2 kWp)</span>
                        <span class="font-headline font-bold text-primary text-xl">Rp 22.000.000</span>
                    </div>
                    <div class="flex justify-between items-center py-4 border-b border-outline-variant/10">
                        <span class="text-on-surface-variant font-medium text-sm">Annual Bill Savings</span>
                        <span class="font-headline font-bold text-secondary text-xl">Rp 4.500.000</span>
                    </div>
                    <div class="flex justify-between items-center pt-8">
                        <div>
                            <p class="text-on-surface-variant text-[10px] uppercase font-bold tracking-widest">Payback time</p>
                            <p class="font-headline font-black text-4xl text-tertiary mt-1">4.9 Years</p>
                        </div>
                        <div class="text-right">
                            <p class="text-on-surface-variant text-[10px] uppercase font-bold tracking-widest">Profit Year 6-25</p>
                            <p class="font-headline font-black text-2xl text-on-surface mt-1">Rp 90jt+</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Bottom CTA --}}
    <section class="px-8 lg:px-20 py-32">
        <div class="max-w-3xl mx-auto text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-8">
                <span class="material-symbols-outlined text-3xl text-primary" style="font-variation-settings: 'FILL' 1;">solar_power</span>
            </div>
            <h2 class="font-headline text-5xl font-extrabold text-on-surface tracking-tight leading-tight">Ready to see your savings?</h2>
            <p class="text-on-surface-variant mt-5 mb-12 text-lg max-w-lg mx-auto">Our simulator runs over 50 data points for your specific roof in under 30 seconds. No account needed.</p>
            <a href="{{ route('calculator') }}"
               class="solar-gradient text-white px-12 py-5 rounded-2xl font-headline font-extrabold text-xl shadow-2xl shadow-primary/20 hover:-translate-y-1 active:scale-95 transition-all inline-flex items-center gap-3">
                <span class="material-symbols-outlined">bolt</span>
                Start Free Simulation
            </a>
        </div>
    </section>

</div>
@endsection

