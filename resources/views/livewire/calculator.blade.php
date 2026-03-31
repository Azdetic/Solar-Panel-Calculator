<!-- Main Content Shell -->
<main class="flex-grow pt-16 pb-8 px-6 max-w-[1600px] mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
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
    
    <!-- Right Panel: Input Form - Optimized for density -->
    <section class="lg:col-span-4 flex flex-col gap-4 relative z-10">
        <div class="bg-surface-container-low p-6 rounded-xl shadow-sm border-l-4 border-tertiary">
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
                                <select wire:model.live="tariff" class="w-full bg-surface-container-high border-none rounded-lg p-2 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all appearance-none cursor-pointer font-body text-sm py-2">
                                    <option value="R-1 / 1.300 VA">R-1 / 1.300 VA</option>
                                    <option value="R-1 / 2.200 VA">R-1 / 2.200 VA</option>
                                    <option value="R-2 / 3.500 VA">R-2 / 3.500 VA</option>
                                    <option value="R-3 / 6.600 VA +">R-3 / 6.600 VA +</option>
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
                
                <!-- Budget Slider - More Compact -->
                <div class="space-y-2 pt-1">
                    <div class="flex justify-between items-end">
                        <label class="font-label text-[10px] font-bold text-outline-variant uppercase">Estimasi Budget</label>
                        <span class="font-headline font-extrabold text-primary text-lg leading-none">Rp {{ number_format($budget / 1000000, 0) }} jt</span>
                    </div>
                    <input wire:model.live="budget" class="w-full h-1.5 bg-surface-container-highest rounded-lg appearance-none cursor-pointer accent-primary" max="100000000" min="10000000" step="1000000" type="range"/>
                    <div class="flex justify-between text-[8px] font-bold text-outline uppercase tracking-widest">
                        <span>10 Jt</span>
                        <span>100 Jt</span>
                    </div>
                </div>
                
                <!-- CTA Button - Prominent in compact space -->
                <button class="w-full solar-gradient text-white py-4 rounded-xl font-headline font-extrabold text-base shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all mt-2 flex items-center justify-center gap-2">
                    Hitung Sekarang
                    <span class="material-symbols-outlined text-xl">bolt</span>
                </button>
            </div>
        </div>
        
        <!-- Micro-Graph Decor - Smaller and denser -->
        <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm hidden lg:block border border-outline-variant/10 mt-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-bold text-outline-variant uppercase tracking-wider">Efisiensi Produksi</span>
                <span class="text-[9px] bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded-full font-bold">+12%</span>
            </div>
            <div class="h-12 flex items-end gap-1 px-1">
                <div class="bg-secondary/20 w-full h-[40%] rounded-t-sm"></div>
                <div class="bg-secondary/30 w-full h-[55%] rounded-t-sm"></div>
                <div class="bg-secondary/40 w-full h-[70%] rounded-t-sm"></div>
                <div class="bg-secondary/60 w-full h-[65%] rounded-t-sm"></div>
                <div class="bg-secondary/70 w-full h-[85%] rounded-t-sm"></div>
                <div class="bg-secondary w-full h-[100%] rounded-t-sm"></div>
                <div class="bg-secondary/80 w-full h-[90%] rounded-t-sm"></div>
                <div class="bg-secondary/60 w-full h-[75%] rounded-t-sm"></div>
                <div class="bg-secondary/40 w-full h-[60%] rounded-t-sm"></div>
                <div class="bg-secondary/30 w-full h-[45%] rounded-t-sm"></div>
                <div class="bg-secondary/20 w-full h-[35%] rounded-t-sm"></div>
            </div>
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
