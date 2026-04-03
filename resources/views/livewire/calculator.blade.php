<!-- Main Content Shell -->
<main class="flex-grow pt-20 pb-8 px-8 lg:px-12 w-full grid grid-cols-1 lg:grid-cols-12 gap-6 xl:gap-8 items-start">
    
    <!-- Left Panel: Interactive Map - Made slightly more prominent -->
    <section class="lg:col-span-8 h-[400px] lg:h-[780px] relative rounded-xl overflow-hidden shadow-sm group">
        
        <div class="absolute inset-0 z-0 bg-surface-container-high" wire:ignore>
            <div id="map" style="width: 100%; height: 100%; min-height: 500px; z-index: 1;"></div>
        </div>
        
        <!-- Search Bar Overlay -->
        <div class="absolute top-4 left-4 right-4 z-10">
            <div class="glass-overlay flex items-center px-4 py-2 rounded-lg shadow-lg border border-white/20">
                <span class="material-symbols-outlined text-outline mr-2 text-xl">search</span>
                <input onkeypress="window.searchLocation(event)" class="bg-transparent border-none focus:ring-0 w-full text-on-surface font-body text-sm py-1" placeholder="Search address or city (Press Enter)..." type="text" value="{{ $locationName }}"/>
                <span onclick="window.locateUser()" class="material-symbols-outlined text-outline ml-2 cursor-pointer hover:text-primary transition-colors text-xl" title="Use Current Location">my_location</span>
            </div>
        </div>
        
        <!-- Map Controls Overlay -->
        <div class="absolute bottom-4 right-4 z-10 flex flex-col gap-2">
            <button onclick="window.zoomMapIn()" class="glass-overlay w-8 h-8 rounded-lg shadow-md flex items-center justify-center text-on-surface hover:bg-surface-container-lowest transition-all">
                <span class="material-symbols-outlined text-xl">add</span>
            </button>
            <button onclick="window.zoomMapOut()" class="glass-overlay w-8 h-8 rounded-lg shadow-md flex items-center justify-center text-on-surface hover:bg-surface-container-lowest transition-all">
                <span class="material-symbols-outlined text-xl">remove</span>
            </button>
            <button onclick="window.toggleLayer()" title="Toggle Map Mode (Satellite/Street)" class="glass-overlay w-8 h-8 rounded-lg shadow-md flex items-center justify-center text-on-surface hover:bg-surface-container-lowest transition-all">
                <span class="material-symbols-outlined text-xl">layers</span>
            </button>
        </div>
        
        <!-- Precision Reticle Decor -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="w-32 h-32 border border-primary/40 rounded-full animate-pulse flex items-center justify-center">
                <div class="w-1.5 h-1.5 bg-primary rounded-full"></div>
            </div>
        </div>
    </section>
    
    <!-- Right Panel: Input Form and Results -->
    <section class="lg:col-span-4 flex flex-col gap-4 relative z-10" 
             x-data="{ 
                formatCurrency(val) {
                    if (!val) return '';
                    let str = val.toString().replace(/\D/g, '');
                    return str.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
             }">
        <div class="bg-surface-container-low p-6 rounded-2xl shadow-md h-full overflow-y-auto max-h-[780px]">
            @if($errorMessage)
            <div class="mb-4 bg-error-container/80 text-on-error-container p-4 rounded-xl text-sm font-body">
                <strong>Error:</strong><br/>
                {{ $errorMessage }}
            </div>
            @endif

            @if(!$simulationResult)
            <!-- STATE A: INPUT FORM -->
            <div class="mb-5">
                <span class="text-[10px] font-bold text-primary uppercase tracking-widest">Solar Simulator</span>
                <h1 class="font-headline text-2xl font-extrabold text-on-surface mt-1 tracking-tight leading-none">Your building info</h1>
                <p class="text-on-surface-variant text-xs mt-2">Fill in the details below and hit calculate</p>
            </div>
            
            <div class="space-y-5">
                <!-- Location Readout -->
                <div>
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Location</label>
                    <div class="flex items-center gap-2 bg-surface-container-high/60 px-3 py-2.5 rounded-xl mt-1.5" wire:loading.class="opacity-50 animate-pulse" wire:target="updateLocation">
                        <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">location_on</span>
                        <span class="font-body font-medium text-sm text-on-surface line-clamp-1 truncate" title="{{ $locationName }}">{{ $locationName }}</span>
                    </div>
                </div>
                
                <!-- Roof Dimensions -->
                <div>
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Roof size</label>
                    <div class="grid grid-cols-2 gap-3 mt-1.5">
                        <div>
                            <input wire:model.live="length" class="w-full bg-surface-container-high/60 rounded-xl p-3 text-on-surface focus:ring-2 focus:ring-primary/30 focus:outline-none transition-all font-headline font-bold text-sm border-0" placeholder="Length (m)" type="number"/>
                        </div>
                        <div>
                            <input wire:model.live="width" class="w-full bg-surface-container-high/60 rounded-xl p-3 text-on-surface focus:ring-2 focus:ring-primary/30 focus:outline-none transition-all font-headline font-bold text-sm border-0" placeholder="Width (m)" type="number"/>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-2 px-1">
                        <span class="text-[10px] text-on-surface-variant">Total area</span>
                        <span class="text-sm font-headline font-bold text-tertiary">{{ number_format($area, 1) }} m²</span>
                    </div>
                </div>
                
                <!-- Tariff & Bill -->
                <div>
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">PLN tariff</label>
                    <div class="relative mt-1.5">
                        <select wire:model.live="tariff_id" class="w-full bg-surface-container-high/60 rounded-xl p-3 text-on-surface focus:ring-2 focus:ring-primary/30 focus:outline-none transition-all appearance-none cursor-pointer font-body text-sm border-0">
                            @foreach($tariffs as $t)
                                <option value="{{ $t->id }}">{{ $t->name }} (Rp {{ number_format($t->price_per_kwh, 2, ',', '.') }})</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant text-lg">expand_more</span>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Monthly electricity bill</label>
                    <div class="relative mt-1.5">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-xs font-medium">Rp</span>
                        <input wire:model.live="bill" 
                               x-on:input="$el.value = formatCurrency($el.value)"
                               x-init="$el.value = formatCurrency($el.value)"
                               class="w-full bg-surface-container-high/60 rounded-xl p-3 pl-9 text-on-surface focus:ring-2 focus:ring-primary/30 focus:outline-none transition-all font-headline font-bold text-base border-0" 
                               type="text"/>
                    </div>
                </div>
                
                <!-- Budget -->
                <div>
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Budget (max you'd spend)</label>
                    <div class="relative mt-1.5">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-xs font-medium">Rp</span>
                        <input wire:model.live.debounce.500ms="budget" 
                               x-on:input="$el.value = formatCurrency($el.value)"
                               x-init="$el.value = formatCurrency($el.value)"
                               class="w-full bg-surface-container-high/60 rounded-xl p-3 pl-9 text-on-surface focus:ring-2 focus:ring-primary/30 focus:outline-none transition-all font-headline font-bold text-base border-0" 
                               type="text" 
                               placeholder="e.g. 25.000.000"/>
                    </div>
                </div>
                
                <!-- CTA Button -->
                <button wire:click="calculateResult" wire:loading.attr="disabled" class="w-full solar-gradient text-white py-4 rounded-xl font-headline font-extrabold text-base shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2 disabled:opacity-75 disabled:cursor-wait">
                    <span wire:loading.remove wire:target="calculateResult">Calculate Now</span>
                    <span wire:loading wire:target="calculateResult">Fetching NASA data...</span>
                    
                    <span wire:loading.remove wire:target="calculateResult" class="material-symbols-outlined text-xl">bolt</span>
                    <span wire:loading wire:target="calculateResult" class="material-symbols-outlined animate-spin text-xl">progress_activity</span>
                </button>
            </div>
            
            @else
            <!-- STATE B: RESULT VIEW -->
            <div class="flex flex-col h-full animate-fade-in">
                <div class="flex justify-between items-start mb-5 pb-4">
                    <div>
                        <span class="text-[10px] font-bold text-primary uppercase tracking-widest">Results</span>
                        <h2 class="font-headline text-2xl font-extrabold text-on-surface mt-1 leading-none">Your solar report</h2>
                        <div class="flex items-center gap-1 mt-2 text-on-surface-variant text-xs">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            <span class="truncate max-w-[200px]">{{ $locationName }}</span>
                        </div>
                    </div>
                    <button wire:click="$set('simulationResult', null)" class="text-xs font-bold text-primary flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-full hover:bg-primary/15 transition-colors">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Redo
                    </button>
                </div>

                <div class="flex-grow space-y-4">
                    <!-- Highlight Card -->
                    <div class="bg-primary/8 rounded-2xl p-5">
                        <span class="text-[10px] font-bold text-primary uppercase tracking-wider">Recommended system</span>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-4xl font-headline font-extrabold text-on-surface leading-none">{{ $simulationResult['installed_capacity'] }}</span>
                            <span class="text-sm font-medium text-on-surface-variant">kWp</span>
                        </div>
                        <p class="mt-2 text-xs text-on-surface-variant">
                            ~Rp {{ number_format($simulationResult['investment_cost'], 0, ',', '.') }} investment
                            · Limited by {{ $simulationResult['bottleneck'] == 'roof' ? 'roof space' : 'budget' }}
                        </p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-surface-container-high/50 rounded-xl p-3">
                            <span class="text-[10px] text-on-surface-variant font-medium">Monthly savings</span>
                            <p class="text-base font-headline font-bold text-on-surface mt-0.5">Rp {{ number_format($simulationResult['savings_per_month'], 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-surface-container-high/50 rounded-xl p-3">
                            <span class="text-[10px] text-on-surface-variant font-medium">Payback</span>
                            <p class="text-base font-headline font-bold text-on-surface mt-0.5">{{ $simulationResult['payback_years'] }} <span class="text-xs font-normal">years</span></p>
                        </div>
                        <div class="bg-surface-container-high/50 rounded-xl p-3">
                            <span class="text-[10px] text-on-surface-variant font-medium">Yearly output</span>
                            <p class="text-base font-headline font-bold text-on-surface mt-0.5">{{ number_format($simulationResult['production_per_year'], 0, ',', '.') }} <span class="text-xs font-normal">kWh</span></p>
                        </div>
                        <div class="bg-surface-container-high/50 rounded-xl p-3">
                            <span class="text-[10px] text-on-surface-variant font-medium">CO₂ avoided</span>
                            <p class="text-base font-headline font-bold text-on-surface mt-0.5">{{ number_format($simulationResult['co2_per_year'], 0, ',', '.') }} <span class="text-xs font-normal">kg/yr</span></p>
                        </div>
                    </div>

                    <!-- Energy Independence -->
                    <div class="bg-surface-container-high/30 p-4 rounded-xl">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] text-on-surface-variant font-medium">Energy independence</span>
                            <span class="font-bold text-sm text-on-surface">{{ $simulationResult['independence_percent'] }}%</span>
                        </div>
                        <div class="w-full bg-surface-container-highest/50 rounded-full h-2 overflow-hidden">
                            <div class="bg-secondary h-2 rounded-full transition-all" style="width: {{ $simulationResult['independence_percent'] }}%"></div>
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-2">
                            Covers {{ $simulationResult['independence_percent'] }}% of ~{{ $simulationResult['estimated_usage_kwh'] }} kWh/month usage
                        </p>
                    </div>
                    
                    <!-- Calculation Breakdown -->
                    <details class="group rounded-xl overflow-hidden">
                        <summary class="px-4 py-3 text-xs font-bold cursor-pointer text-on-surface-variant hover:text-on-surface transition-colors flex justify-between items-center bg-surface-container-high/40 rounded-xl group-open:rounded-b-none">
                            How did we calculate this?
                            <span class="material-symbols-outlined text-sm transition-transform group-open:rotate-180">expand_more</span>
                        </summary>

                        <div class="px-4 py-4 bg-surface-container-high/20 space-y-4 text-[11px] text-on-surface-variant">

                            {{-- Step 0: NASA API --}}
                            <div class="bg-primary/5 rounded-xl p-3 space-y-1">
                                <p class="font-bold text-primary uppercase tracking-wider text-[10px] flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">satellite_alt</span>
                                    Step 1: NASA POWER API
                                </p>
                                <p class="leading-relaxed">Sent your coordinates ({{ $latitude }}, {{ $longitude }}) to NASA POWER for annual average solar radiation</p>
                                <div class="mt-2 bg-surface-container-high/60 rounded-lg px-3 py-2 font-mono text-[10px] text-on-surface">
                                    ALLSKY_SFC_SW_DWN → <strong>{{ $simulationResult['ghi'] }} kWh/m²/day</strong>
                                </div>
                                <p class="text-[10px] text-on-surface-variant/60 italic">Average daily sunlight energy per m² over multiple years</p>
                            </div>

                            {{-- Step 1: Roof --}}
                            <div class="space-y-1">
                                <p class="font-bold text-on-surface text-[10px]">Step 2: Roof capacity</p>
                                <p>{{ $length }}m × {{ $width }}m = {{ $area }} m², 75% usable</p>
                                <div class="bg-surface-container-high/60 rounded-lg px-3 py-2 font-mono text-[10px] text-on-surface mt-1">
                                    {{ $area }} × 0.75 = {{ round($area * 0.75, 1) }} m² usable
                                    <br>
                                    {{ round($area * 0.75, 1) }} ÷ 6.5 × 0.4 = <strong>{{ $simulationResult['capacity_from_roof'] }} kWp max</strong>
                                </div>
                            </div>

                            {{-- Step 2: Budget --}}
                            <div class="space-y-1">
                                <p class="font-bold text-on-surface text-[10px]">Step 3: Budget capacity</p>
                                <p>~Rp 11jt per kWp installed</p>
                                <div class="bg-surface-container-high/60 rounded-lg px-3 py-2 font-mono text-[10px] text-on-surface mt-1">
                                    Rp {{ number_format($budget, 0, ',', '.') }} ÷ 11,000,000 = <strong>{{ $simulationResult['capacity_from_budget'] }} kWp max</strong>
                                </div>
                            </div>

                            {{-- Step 3: Final capacity --}}
                            <div class="space-y-1">
                                <p class="font-bold text-on-surface text-[10px]">Step 4: Final size</p>
                                <p>Whichever is smaller → {{ $simulationResult['bottleneck'] === 'roof' ? 'roof' : 'budget' }} is the limit</p>
                                <div class="bg-surface-container-high/60 rounded-lg px-3 py-2 font-mono text-[10px] text-on-surface mt-1">
                                    min({{ $simulationResult['capacity_from_roof'] }}, {{ $simulationResult['capacity_from_budget'] }}) = <strong>{{ $simulationResult['installed_capacity'] }} kWp</strong>
                                </div>
                            </div>

                            {{-- Step 4: Production --}}
                            <div class="space-y-1">
                                <p class="font-bold text-on-surface text-[10px]">Step 5: Energy output</p>
                                <p>75% system efficiency (inverter + wiring + temp losses)</p>
                                <div class="bg-surface-container-high/60 rounded-lg px-3 py-2 font-mono text-[10px] text-on-surface mt-1">
                                    {{ $simulationResult['installed_capacity'] }} × {{ $simulationResult['ghi'] }} × 0.75 = {{ round($simulationResult['production_per_month'] / 30, 2) }} kWh/day
                                    <br>× 30 = {{ $simulationResult['production_per_month'] }} kWh/mo
                                    <br>× 12 = <strong>{{ number_format($simulationResult['production_per_year'], 0, ',', '.') }} kWh/yr</strong>
                                </div>
                            </div>

                            {{-- Step 5: Savings --}}
                            <div class="space-y-1">
                                <p class="font-bold text-on-surface text-[10px]">Step 6: Savings</p>
                                <div class="bg-surface-container-high/60 rounded-lg px-3 py-2 font-mono text-[10px] text-on-surface mt-1">
                                    {{ $simulationResult['production_per_month'] }} kWh × PLN tariff = <strong>Rp {{ number_format($simulationResult['savings_per_month'], 0, ',', '.') }}/mo</strong>
                                </div>
                            </div>

                            {{-- Step 6: Payback --}}
                            <div class="space-y-1">
                                <p class="font-bold text-on-surface text-[10px]">Step 7: Payback</p>
                                <div class="bg-surface-container-high/60 rounded-lg px-3 py-2 font-mono text-[10px] text-on-surface mt-1">
                                    Rp {{ number_format($simulationResult['investment_cost'], 0, ',', '.') }} ÷ {{ number_format($simulationResult['savings_per_year'], 0, ',', '.') }}/yr = <strong>{{ $simulationResult['payback_years'] }} years</strong>
                                    <br>25 − {{ $simulationResult['payback_years'] }} = {{ $simulationResult['remaining_lifespan'] }} years free
                                </div>
                            </div>

                            {{-- CO2 --}}
                            <div class="space-y-1">
                                <p class="font-bold text-on-surface text-[10px]">Step 8: CO₂</p>
                                <div class="bg-surface-container-high/60 rounded-lg px-3 py-2 font-mono text-[10px] text-on-surface mt-1">
                                    {{ number_format($simulationResult['production_per_year'], 0, ',', '.') }} kWh × 0.785 = <strong>{{ number_format($simulationResult['co2_per_year'], 0, ',', '.') }} kg CO₂/yr</strong>
                                </div>
                            </div>

                            <p class="text-[9px] text-on-surface-variant/50 pt-2">
                                20% panel eff, 25% system loss, 6.5 m²/panel, 0.4 kWp/panel, Rp 11jt/kWp, 25yr lifespan, CO₂ 0.785 kg/kWh (ID grid). NASA POWER multi-year avg.
                            </p>
                        </div>
                    </details>
                </div>

                <!-- Request Quote CTA -->
                    <div class="mt-6 pt-4 border-t border-outline-variant/20">
                        @if(session('message'))
                            <div class="mb-3 p-3 bg-primary/20 text-on-surface rounded-xl text-sm font-medium flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">check_circle</span>
                                {{ session('message') }}
                            </div>
                        @endif

                        @auth
                            <button wire:click="requestQuotation" class="w-full solar-gradient text-white py-4 rounded-xl font-headline font-extrabold text-base shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="requestQuotation">Request Official Quote</span>
                                <span wire:loading wire:target="requestQuotation">Processing...</span>
                                <span wire:loading.remove wire:target="requestQuotation" class="material-symbols-outlined text-xl">send</span>
                                <span wire:loading wire:target="requestQuotation" class="material-symbols-outlined animate-spin text-xl">progress_activity</span>
                            </button>
                        @endauth
                        @guest
                            <button
                                type="button"
                                x-data="{}"
                                x-on:click="$dispatch('open-guest-modal')"
                                class="group w-full solar-gradient text-white py-4 rounded-xl font-headline font-extrabold text-base shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                            >
                                <span class="tracking-[0.01em]">Request Official Quote</span>
                                <span class="material-symbols-outlined text-xl transition-transform duration-200 group-hover:translate-x-0.5">send</span>
                            </button>
                        @endguest
                    </div>

            </div>
            @endif
        </div>

        

    </section>

    
    <!-- Modals Overlay -->
    
    <!-- 1. Vendor Selection Modal (Logged In) -->
    @if($isVendorModalOpen)
    <div class="fixed inset-0 z-100 flex items-center justify-center bg-black/60 backdrop-blur-sm animate-fade-in" wire:ignore.self>
        <div class="bg-surface-container p-6 lg:p-8 rounded-3xl w-full max-w-md shadow-2xl relative">
            <h3 class="font-headline font-extrabold text-2xl text-on-surface">Pick a Vendor</h3>
            <p class="text-sm text-on-surface-variant mt-2">Who do you want to send your quote request to for this setup?</p>
            
            <div class="mt-6 space-y-3 max-h-[40vh] overflow-y-auto pr-2 custom-scrollbar">
                @forelse($vendorsList as $vendor)
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-outline-variant/30 hover:bg-surface-container-highest cursor-pointer transition-colors {{ $selectedVendorId == $vendor->id ? 'bg-primary/10 border-primary' : '' }}">
                        <input type="radio" wire:model.live="selectedVendorId" value="{{ $vendor->id }}" class="text-primary focus:ring-primary w-5 h-5">
                        <div>
                            <p class="font-bold text-on-surface">{{ $vendor->name }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $vendor->email }}</p>
                        </div>
                    </label>
                @empty
                    <p class="text-sm text-on-surface-variant text-center py-4">No vendors available at the moment.</p>
                @endforelse
            </div>

            <div class="mt-8 flex gap-3">
                <button wire:click="closeVendorModal" class="flex-1 py-3 px-4 rounded-xl font-bold text-on-surface bg-surface-container-highest hover:bg-surface-container-high transition-colors">Cancel</button>
                <button wire:click="submitQuotationRequest" class="flex-1 py-3 px-4 rounded-xl font-bold text-white bg-primary hover:bg-primary/90 transition-colors disabled:opacity-50" {{ $selectedVendorId ? '' : 'disabled' }}>Send Request</button>
            </div>
        </div>
    </div>
    @endif

    <!-- 2. Guest Login Prompt Modal (Not Logged In) -->
    <div x-data="{ open: false }" x-show="open" @open-guest-modal.window="open = true" class="fixed inset-0 z-100 flex items-center justify-center bg-black/60 backdrop-blur-sm animate-fade-in" style="display: none;">
        <div class="bg-surface-container p-6 lg:p-8 rounded-3xl w-full max-w-sm shadow-2xl relative" @click.away="open = false">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-6 mx-auto">
                <span class="material-symbols-outlined text-primary text-3xl">login</span>
            </div>
            <h3 class="font-headline font-extrabold text-2xl text-on-surface text-center">Ready to get a quote?</h3>
            <p class="text-sm text-on-surface-variant mt-3 text-center">You need an account to connect with a vendor, so log in or sign up first!</p>
            
            <div class="mt-8 flex flex-col gap-3">
                <a href="{{ route('login') }}" class="w-full py-3 px-4 rounded-xl font-bold text-white bg-primary text-center hover:bg-primary/90 transition-colors">Log In to Request a Quote</a>
                <button @click="open = false" class="w-full py-3 px-4 rounded-xl font-bold text-on-surface bg-surface-container-highest hover:bg-surface-container-high transition-colors">Maybe Later</button>
            </div>
        </div>
    </div>

    <!-- Sun-Trace Background Decoration -->
    <div class="fixed top-[-5%] right-[-2%] w-[35vw] h-[35vw] bg-primary-fixed-dim/5 blur-[100px] rounded-full pointer-events-none z-[-1]"></div>
    <div class="fixed bottom-[-5%] left-[-2%] w-[25vw] h-[25vw] bg-secondary-fixed-dim/5 blur-[80px] rounded-full pointer-events-none z-[-1]"></div>

<script>
    document.addEventListener('livewire:navigated', () => {
          if (window.myMapInstance) {
              window.myMapInstance.remove();
          }
        // Init Map at default pos
        const map = L.map('map', {
            zoomControl: false // Disable default zoom so we can hook custom buttons
        }).setView([{{ $latitude }}, {{ $longitude }}], 18);

        // 1. OpenStreetMap Standard Tiles
        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        });

        // 2. Esri Satellite Tiles
        const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri'
        });

        // Current active layer logic
        let isSatellite = false;
        osmLayer.addTo(map); // Default active layer

        // Layer Toggle Hook
        window.toggleLayer = () => {
            if (isSatellite) {
                map.removeLayer(satelliteLayer);
                osmLayer.addTo(map);
                isSatellite = false;
            } else {
                map.removeLayer(osmLayer);
                satelliteLayer.addTo(map);
                isSatellite = true;
            }
        };

        // Add Draggable Marker
        let marker = L.marker([{{ $latitude }}, {{ $longitude }}], {
            draggable: true
        }).addTo(map);

        // Update Livewire State when dragged
        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            @this.call('updateLocation', position.lat, position.lng);
        });

        // Click to move marker
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            map.flyTo(e.latlng, map.getZoom());
            @this.call('updateLocation', e.latlng.lat, e.latlng.lng);
        });

        // Hook Custom Zoom Controls
        window.zoomMapIn = () => map.zoomIn();
        window.zoomMapOut = () => map.zoomOut();

        // Geolocation hook for "my_location" button
        window.locateUser = () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const latlng = [lat, lng];

                        // Fly to location
                        marker.setLatLng(latlng);
                        map.flyTo(latlng, 18);

                        // Tell Livewire
                        @this.call('updateLocation', lat, lng);
                    },
                    (error) => {
                        alert('Failed to get location: ' + error.message);
                    },
                    { enableHighAccuracy: true }
                );
            } else {
                alert('Your browser does not support fitur lokasi GPS.');
            }
        };

        // Geocoding Search Hook
        window.searchLocation = (event) => {
            if (event.key === 'Enter') {
                const query = event.target.value;
                if (!query) return;

                event.target.disabled = true;
                
                // Fetch from Nominatim API
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        event.target.disabled = false;
                        if (data && data.length > 0) {
                            const lat = parseFloat(data[0].lat);
                            const lon = parseFloat(data[0].lon);
                            const latlng = [lat, lon];
                            
                            marker.setLatLng(latlng);
                            map.flyTo(latlng, 18);
                            
                            // Tell Livewire
                            @this.call('updateLocation', lat, lon, data[0].display_name.split(',').slice(0, 2).join(', '));
                        } else {
                            alert('Location not found! Try another keyword.');
                        }
                    })
                    .catch(err => {
                        event.target.disabled = false;
                        alert('Network error occurred.');
                    });
            }
        };
    });
</script>
</main>


