<!-- Main Content Shell -->
<main class="flex-grow pt-20 pb-8 px-8 lg:px-12 w-full grid grid-cols-1 lg:grid-cols-12 gap-6 xl:gap-8 items-start">
    
    <!-- Left Panel: Interactive Map - Made slightly more prominent -->
    <section class="lg:col-span-8 h-[400px] lg:h-[780px] relative rounded-xl overflow-hidden shadow-sm group">
        
        <div class="absolute inset-0 z-0 bg-surface-container-high" wire:ignore>
            <div id="map" class="w-full h-full"></div>
        </div>
        
        <!-- Search Bar Overlay -->
        <div class="absolute top-4 left-4 right-4 z-10">
            <div class="glass-overlay flex items-center px-4 py-2 rounded-lg shadow-lg border border-white/20">
                <span class="material-symbols-outlined text-outline mr-2 text-xl">search</span>
                <input onkeypress="window.searchLocation(event)" class="bg-transparent border-none focus:ring-0 w-full text-on-surface font-body text-sm py-1" placeholder="Cari alamat atau kota (Tekan Enter)..." type="text" value="{{ $locationName }}"/>
                <span onclick="window.locateUser()" class="material-symbols-outlined text-outline ml-2 cursor-pointer hover:text-primary transition-colors text-xl" title="Gunakan Lokasi Saat Ini">my_location</span>
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
            <button onclick="window.toggleLayer()" title="Ganti Mode Peta (Satelit/Jalan)" class="glass-overlay w-8 h-8 rounded-lg shadow-md flex items-center justify-center text-on-surface hover:bg-surface-container-lowest transition-all">
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
    <section class="lg:col-span-4 flex flex-col gap-4 relative z-10">
        <div class="bg-surface-container-low p-6 rounded-xl shadow-sm border-l-4 border-tertiary h-full overflow-y-auto max-h-[780px]">
            @if($errorMessage)
            <div class="mb-4 bg-error-container text-on-error-container p-4 rounded-lg text-sm font-body shadow-sm border border-error">
                <strong>Calculation Error:</strong><br/>
                {{ $errorMessage }}
            </div>
            @endif

            @if(!$simulationResult)
            <!-- STATE A: INPUT FORM -->
            <div class="mb-5">
                <span class="label-md font-bold text-primary uppercase tracking-widest text-[10px]">Simulasi Energi</span>
                <h1 class="font-headline text-2xl font-extrabold text-on-surface mt-1 tracking-tight leading-none">Detail Kalkulasi</h1>
                <p class="text-on-surface-variant text-xs mt-1.5 leading-tight">Parameter gedung untuk proyeksi penghematan presisi.</p>
            </div>
            
            <div class="space-y-4">
                <!-- Location Readout -->
                <div class="space-y-1.5">
                    <label class="font-label text-[10px] font-bold text-outline-variant uppercase">Lokasi Terpilih</label>
                    <div class="flex items-center gap-2 bg-surface-container-high px-3 py-2 rounded-lg" wire:loading.class="opacity-50 animate-pulse" wire:target="updateLocation">
                        <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">location_on</span>
                        <span class="font-body font-semibold text-sm text-on-surface line-clamp-1 truncate" title="{{ $locationName }}">{{ $locationName }}</span>
                    </div>
                </div>
                
                <!-- Compact Input Group -->
                <div class="bg-surface-container-high/40 p-3 rounded-xl space-y-4">
                    <!-- Roof Dimensions Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="font-label text-[10px] font-bold text-outline-variant uppercase">Panjang (m)</label>
                            <input wire:model.live="length" class="w-full bg-surface-container-high border-none rounded-lg p-2 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-headline font-bold text-sm" placeholder="0" type="number"/>
                        </div>
                        <div class="space-y-1">
                            <label class="font-label text-[10px] font-bold text-outline-variant uppercase">Lebar (m)</label>
                            <input wire:model.live="width" class="w-full bg-surface-container-high border-none rounded-lg p-2 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-headline font-bold text-sm" placeholder="0" type="number"/>
                        </div>
                    </div>
                    
                    <!-- Area Calculation Result (Integrated) -->
                    <div class="flex justify-between items-center px-1 border-y border-outline-variant/20 py-2">
                        <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-tighter">Luas Area Atap</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-xl font-headline font-extrabold text-tertiary leading-none">{{ number_format($area, 1) }}</span>
                            <span class="text-[10px] font-body font-bold text-on-surface-variant uppercase">m²</span>
                        </div>
                    </div>
                    
                    <!-- Tariff & Bill -->
                    <div class="grid grid-cols-1 gap-3">
                        <div class="space-y-1">
                            <label class="font-label text-[10px] font-bold text-outline-variant uppercase">Golongan Tarif PLN</label>
                            <div class="relative">
                                <select wire:model.live="tariff_id" class="w-full bg-surface-container-high border-none rounded-lg p-2 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all appearance-none cursor-pointer font-body text-sm py-2">
                                    @foreach($tariffs as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} (Rp {{ number_format($t->price_per_kwh, 2, ',', '.') }})</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-outline text-lg">expand_more</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="font-label text-[10px] font-bold text-outline-variant uppercase">Rata-rata Tagihan Bulanan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold text-xs">Rp</span>
                                <input wire:model.live="bill" class="w-full bg-surface-container-high border-none rounded-lg p-2 pl-9 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-headline font-bold text-base" type="text"/>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Budget Input - UI Friendly -->
                <div class="space-y-1 pt-1">
                    <label class="font-label text-[10px] font-bold text-outline-variant uppercase flex justify-between">
                        Estimasi Budget (Maksimal Biaya)
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold text-xs">Rp</span>
                        <input wire:model.live.debounce.500ms="budget" class="w-full bg-surface-container-high border-none rounded-lg p-3 pl-9 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-headline font-bold text-base" type="number" min="1000000" placeholder="Contoh: 25000000"/>
                    </div>
                </div>
                
                <!-- CTA Button -->
                <button wire:click="calculateResult" wire:loading.attr="disabled" class="w-full solar-gradient text-white py-4 rounded-xl font-headline font-extrabold text-base shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all mt-2 flex items-center justify-center gap-2 disabled:opacity-75 disabled:cursor-wait">
                    <span wire:loading.remove wire:target="calculateResult">Hitung Sekarang</span>
                    <span wire:loading wire:target="calculateResult">Processing NASA Data...</span>
                    
                    <span wire:loading.remove wire:target="calculateResult" class="material-symbols-outlined text-xl">bolt</span>
                    <span wire:loading wire:target="calculateResult" class="material-symbols-outlined animate-spin text-xl">progress_activity</span>
                </button>
            </div>
            
            @else
            <!-- STATE B: RESULT VIEW -->
            <div class="flex flex-col h-full animate-fade-in">
                <div class="flex justify-between items-start mb-6 border-b border-outline-variant/20 pb-4">
                    <div>
                        <span class="label-md font-bold text-primary uppercase tracking-widest text-[10px]">Simulation Result</span>
                        <h2 class="font-headline text-2xl font-extrabold text-on-surface mt-1 leading-none">Feasibility Report</h2>
                        <div class="flex items-center gap-1 mt-2 text-on-surface-variant text-xs">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            <span class="truncate max-w-[200px]">{{ $locationName }}</span>
                        </div>
                    </div>
                    <button wire:click="$set('simulationResult', null)" class="text-xs font-bold text-tertiary hover:underline flex items-center gap-1 bg-surface-container-high px-3 py-1.5 rounded-full hover:bg-surface-container-highest transition-colors">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Recalculate
                    </button>
                </div>

                <div class="flex-grow space-y-4">
                    <!-- Highlight Card -->
                    <div class="bg-primary/10 border border-primary/20 rounded-xl p-4">
                        <span class="text-[10px] font-bold text-primary uppercase tracking-wider">Recommended System Size</span>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-3xl font-headline font-extrabold text-on-surface leading-none">{{ $simulationResult['installed_capacity'] }}</span>
                            <span class="text-sm font-bold text-on-surface-variant">kWp</span>
                        </div>
                        <div class="mt-2 text-xs text-on-surface-variant">
                            Est. Investment: <strong class="text-on-surface font-body">Rp {{ number_format($simulationResult['investment_cost'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="mt-1 flex items-center gap-1 text-[10px] text-tertiary">
                            <span class="material-symbols-outlined text-[12px]">info</span>
                            Limited by: {{ $simulationResult['bottleneck'] == 'roof' ? 'Roof Space' : 'Available Budget' }}
                        </div>
                    </div>

                    <!-- 4 Stat Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-surface-container-high rounded-xl p-3 shadow-sm border border-outline/5 hover:border-outline/20 transition-all">
                            <span class="text-[10px] text-outline-variant font-bold uppercase tracking-wider">Monthly Savings</span>
                            <p class="text-base font-headline font-bold text-on-surface mt-1">Rp {{ number_format($simulationResult['savings_per_month'], 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-surface-container-high rounded-xl p-3 shadow-sm border border-outline/5 hover:border-outline/20 transition-all">
                            <span class="text-[10px] text-outline-variant font-bold uppercase tracking-wider">Payback Period</span>
                            <p class="text-base font-headline font-bold text-on-surface mt-1">{{ $simulationResult['payback_years'] }} <span class="text-xs font-normal">Years</span></p>
                        </div>
                        <div class="bg-surface-container-high rounded-xl p-3 shadow-sm border border-outline/5 hover:border-outline/20 transition-all">
                            <span class="text-[10px] text-outline-variant font-bold uppercase tracking-wider">Yearly Production</span>
                            <p class="text-base font-headline font-bold text-on-surface mt-1">{{ number_format($simulationResult['production_per_year'], 0, ',', '.') }} <span class="text-xs font-normal">kWh</span></p>
                        </div>
                        <div class="bg-surface-container-high rounded-xl p-3 shadow-sm border border-outline/5 hover:border-outline/20 transition-all">
                            <span class="text-[10px] text-outline-variant font-bold uppercase tracking-wider">CO₂ Reduction</span>
                            <p class="text-base font-headline font-bold text-on-surface mt-1">{{ number_format($simulationResult['co2_per_year'], 0, ',', '.') }} <span class="text-xs font-normal">kg/yr</span></p>
                        </div>
                    </div>

                    <!-- Energy Independence Bar -->
                    <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/10">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">Energy Independence</span>
                            <span class="font-bold text-sm text-on-surface">{{ $simulationResult['independence_percent'] }}%</span>
                        </div>
                        <div class="w-full bg-surface-container-highest rounded-full h-2.5 overflow-hidden">
                            <div class="bg-secondary h-2.5 rounded-full" style="width: {{ $simulationResult['independence_percent'] }}%"></div>
                        </div>
                        <p class="text-[10px] text-outline-variant mt-2 leading-tight">
                            Covers {{ $simulationResult['independence_percent'] }}% of your estimated monthly usage ({{ $simulationResult['estimated_usage_kwh'] }} kWh/mo).
                        </p>
                    </div>
                    
                    <!-- Accordion Details -->
                    <details class="group bg-surface-container-low border border-outline-variant/20 rounded-xl overflow-hidden shadow-sm">
                        <summary class="px-4 py-3 text-xs font-bold cursor-pointer text-on-surface hover:bg-surface-container-high transition-colors flex justify-between items-center">
                            View Detailed Calculations
                            <span class="material-symbols-outlined text-sm transition-transform group-open:rotate-180">expand_more</span>
                        </summary>
                        <div class="px-4 py-4 bg-surface-container-highest/20 text-[11px] text-on-surface-variant space-y-2 border-t border-outline-variant/20">
                            <p class="font-bold text-on-surface uppercase tracking-wider mb-2">Technical Specs</p>
                            <div class="grid grid-cols-2 gap-1 gap-x-4">
                                <span>GHI (NASA POWER):</span> <strong class="text-right">{{ $simulationResult['ghi'] }} kWh/m²/day</strong>
                                <span>Roof Capacity Limit:</span> <strong class="text-right">{{ $simulationResult['capacity_from_roof'] }} kWp</strong>
                                <span>Budget Capacity Limit:</span> <strong class="text-right">{{ $simulationResult['capacity_from_budget'] }} kWp</strong>
                                <span>Free Energy After BEP:</span> <strong class="text-right">{{ $simulationResult['remaining_lifespan'] }} Years</strong>
                            </div>
                            <div class="pt-2 mt-2 border-t border-outline-variant/20 text-[9px] text-outline italic">
                                * NASA POWER climatology data provides multi-year average solar radiation figures appropriate for feasibility studies. Panel efficiency 20%, System losses 25%. Market price approx Rp 11jt/kWp.
                            </div>
                        </div>
                    </details>
                </div>
            </div>
            @endif
        </div>

        

    </section>

    <!-- Sun-Trace Background Decoration -->
    <div class="fixed top-[-5%] right-[-2%] w-[35vw] h-[35vw] bg-primary-fixed-dim/5 blur-[100px] rounded-full pointer-events-none z-[-1]"></div>
    <div class="fixed bottom-[-5%] left-[-2%] w-[25vw] h-[25vw] bg-secondary-fixed-dim/5 blur-[80px] rounded-full pointer-events-none z-[-1]"></div>

<script>
    document.addEventListener('livewire:initialized', () => {
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
                        alert('Gagal mengambil lokasi: ' + error.message);
                    },
                    { enableHighAccuracy: true }
                );
            } else {
                alert('Browser Anda tidak mendukung fitur lokasi GPS.');
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
                            alert('Lokasi tidak ditemukan! Coba kata kunci lain.');
                        }
                    })
                    .catch(err => {
                        event.target.disabled = false;
                        alert('Terjadi kesalahan jaringan.');
                    });
            }
        };
    });
</script>
</main>
