<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'E-Kubur - Sistem Pengurusan Jenazah' }}</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Leaflet CSS and JS (OpenStreetMap) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        .action-icon {
            font-size: 16px !important;
            line-height: 1 !important;
        }
        .action-icon .material-icons {
            font-size: 16px !important;
            line-height: 1 !important;
        }
        .material-icons.text-\[11px\] {
            font-size: 14px !important;
            line-height: 1 !important;
        }
        /* Override global Material Icons CSS */
        .material-icons.text-\[10px\] {
            font-size: 18px !important;
            line-height: 1 !important;
        }
        .material-icons.text-xs {
            font-size: 12px !important;
            line-height: 1 !important;
        }
        .material-icons.text-sm {
            font-size: 14px !important;
            line-height: 1 !important;
        }
        
        /* Maps styling */
        #map {
            width: 100%;
            height: 400px;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            z-index: 1;
        }
        
        /* Mobile responsive adjustments */
        @media (max-width: 640px) {
            .map-controls {
                padding: 16px;
            }
            
            .map-controls button {
                width: 100%;
                justify-content: center;
            }
            
            .coordinate-display {
                font-size: 11px;
                padding: 12px 16px;
            }
            
            /* Stack form fields on mobile */
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
        
        .map-controls {
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        
        .coordinate-display {
            background: #f3f4f6;
            padding: 8px 12px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 12px;
            color: #374151;
        }
        
        /* Custom marker icon */
        .custom-marker {
            background: #dc2626;
            border: 2px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .custom-marker::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 8px solid #dc2626;
        }
    </style>
    
    <script>
        function formatIC(input) {
            // Remove all non-digits
            let value = input.value.replace(/\D/g, '');
            
            // Limit to 12 digits
            if (value.length > 12) {
                value = value.substring(0, 12);
            }
            
            // Format with dashes: DDMMYY-DD-DDDD
            if (value.length >= 6) {
                value = value.substring(0, 6) + '-' + value.substring(6);
            }
            if (value.length >= 9) {
                value = value.substring(0, 9) + '-' + value.substring(9);
            }
            
            input.value = value;
        }
        
        // Leaflet Maps functionality
        let map, marker;
        let defaultLat = {{ \App\Models\Tetapan::getDefaultLatitude() }}; // Default latitude from settings
        let defaultLng = {{ \App\Models\Tetapan::getDefaultLongitude() }}; // Default longitude from settings
        let currentLayer = 'street'; // Track current layer
        
        function initMap() {
            // Get coordinates from form or use defaults
            let lat = parseFloat(document.getElementById('latitude').value) || defaultLat;
            let lng = parseFloat(document.getElementById('longitude').value) || defaultLng;
            
            // Create map
            map = L.map('map').setView([lat, lng], 13);
            
            // Define map layers
            const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            });
            
            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '© Esri — Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
                maxZoom: 19
            });
            
            // Add default layer
            streetLayer.addTo(map);
            
            // Create custom marker icon
            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: '',
                iconSize: [20, 20],
                iconAnchor: [10, 20]
            });
            
            // Create marker
            marker = L.marker([lat, lng], {
                icon: customIcon,
                draggable: true
            }).addTo(map);
            
            // Add click listener to map
            map.on('click', function(event) {
                placeMarker(event.latlng);
            });
            
            // Add drag listener to marker
            marker.on('dragend', function(event) {
                const position = event.target.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });
            
            // Store layers for switching
            map.streetLayer = streetLayer;
            map.satelliteLayer = satelliteLayer;
            
            // Update coordinates display
            updateCoordinates(lat, lng);
        }
        
        function switchMapLayer() {
            const layerButton = document.getElementById('layer-button');
            const layerIcon = document.getElementById('layer-icon');
            const layerText = document.getElementById('layer-text');
            const layerButtonMobile = document.getElementById('layer-button-mobile');
            const layerIconMobile = document.getElementById('layer-icon-mobile');
            const layerTextMobile = document.getElementById('layer-text-mobile');
            
            if (currentLayer === 'street') {
                // Switch to satellite
                map.removeLayer(map.streetLayer);
                map.satelliteLayer.addTo(map);
                currentLayer = 'satellite';
                layerIcon.textContent = 'map';
                layerText.textContent = 'Peta Jalan';
                layerIconMobile.textContent = 'map';
                layerTextMobile.textContent = 'Peta Jalan';
            } else {
                // Switch to street
                map.removeLayer(map.satelliteLayer);
                map.streetLayer.addTo(map);
                currentLayer = 'street';
                layerIcon.textContent = 'satellite';
                layerText.textContent = 'Satelit';
                layerIconMobile.textContent = 'satellite';
                layerTextMobile.textContent = 'Satelit';
            }
        }
        
        function placeMarker(latLng) {
            marker.setLatLng(latLng);
            map.setView(latLng);
            updateCoordinates(latLng.lat, latLng.lng);
        }
        
        function updateCoordinates(lat, lng) {
            // Update form fields
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            
            // Update desktop display
            const desktopDisplay = document.getElementById('coordinate-display');
            if (desktopDisplay) {
                desktopDisplay.textContent = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            }
            
            // Update mobile display
            const mobileDisplay = document.getElementById('coordinate-display-mobile');
            if (mobileDisplay) {
                mobileDisplay.textContent = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            }
        }
        
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        placeMarker({lat: lat, lng: lng});
                    },
                    function() {
                        alert('Tidak dapat mendapatkan lokasi semasa. Sila pilih lokasi secara manual pada peta.');
                    }
                );
            } else {
                alert('Geolokasi tidak disokong oleh browser ini.');
            }
        }
        
        function resetToDefault() {
            placeMarker({lat: defaultLat, lng: defaultLng});
        }
        
        // Initialize map when page loads
        window.addEventListener('load', function() {
            // Small delay to ensure DOM is ready
            setTimeout(initMap, 100);
        });
    </script>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">
    <x-double-navbar :user="$user" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Rekod Kematian</h1>
                        <p class="text-xs text-gray-600">Kemaskini maklumat rekod kematian</p>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('kematian.update', $kematian) }}" class="space-y-8" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Info Orang Meninggal Section -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                            <span class="material-icons text-blue-600 mr-2 text-lg">person</span>
                            Info Orang Meninggal
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 form-grid">
                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-xs font-medium text-gray-700 mb-2">Nama Penuh *</label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama', $kematian->nama) }}" required
                                    class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900 @error('nama') border-b-red-500 @enderror"
                                    placeholder="Contoh: Ahmad bin Ali">
                                @error('nama')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tarikh Lahir -->
                            <div>
                                <label for="tarikh_lahir" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Lahir *</label>
                                <input type="date" id="tarikh_lahir" name="tarikh_lahir" value="{{ old('tarikh_lahir', $kematian->tarikh_lahir ? $kematian->tarikh_lahir->format('Y-m-d') : '') }}" required
                                    class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none text-gray-700 @error('tarikh_lahir') border-b-red-500 @enderror">
                                @error('tarikh_lahir')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No. IC -->
                            <div>
                                <label for="no_ic" class="block text-xs font-medium text-gray-700 mb-2">Nombor IC *</label>
                                <input type="text" id="no_ic" name="no_ic" value="{{ old('no_ic', $kematian->no_ic) }}" required
                                    class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900 @error('no_ic') border-b-red-500 @enderror"
                                    placeholder="Contoh: 891230-13-1581" maxlength="14" oninput="formatIC(this)">
                                @error('no_ic')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tarikh Meninggal -->
                            <div>
                                <label for="tarikh_meninggal" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Meninggal *</label>
                                <input type="date" id="tarikh_meninggal" name="tarikh_meninggal" value="{{ old('tarikh_meninggal', $kematian->tarikh_meninggal ? $kematian->tarikh_meninggal->format('Y-m-d') : '') }}" required
                                    class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none text-gray-700 @error('tarikh_meninggal') border-b-red-500 @enderror">
                                @error('tarikh_meninggal')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Longitude -->
                            <div>
                                <label for="longitude" class="block text-xs font-medium text-gray-700 mb-2">Longitude *</label>
                                <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $kematian->longitude) }}" required
                                    class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900 @error('longitude') border-b-red-500 @enderror"
                                    placeholder="Contoh: {{ \App\Models\Tetapan::getDefaultLongitude() }}">
                                @error('longitude')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Latitude -->
                            <div>
                                <label for="latitude" class="block text-xs font-medium text-gray-700 mb-2">Latitude *</label>
                                <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $kematian->latitude) }}" required
                                    class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900 @error('latitude') border-b-red-500 @enderror"
                                    placeholder="Contoh: {{ \App\Models\Tetapan::getDefaultLatitude() }}">
                                @error('latitude')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- OpenStreetMap Section -->
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold text-blue-900 mb-3 flex items-center">
                                <span class="material-icons text-blue-600 mr-2 text-sm">map</span>
                                Pilih Lokasi pada Peta
                            </h3>
                            
                            <!-- Map Controls -->
                            <div class="map-controls mb-3">
                                <!-- Desktop Layout -->
                                <div class="hidden md:flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <button type="button" onclick="getCurrentLocation()" class="flex items-center px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700">
                                            <span class="material-icons text-xs mr-2">my_location</span>
                                            Lokasi Semasa
                                        </button>
                                        <button type="button" onclick="resetToDefault()" class="flex items-center px-3 py-2 bg-gray-600 text-white text-xs rounded-md hover:bg-gray-700">
                                            <span class="material-icons text-xs mr-2">home</span>
                                            Lokasi Default
                                        </button>
                                        <button type="button" id="layer-button" onclick="switchMapLayer()" class="flex items-center px-3 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700">
                                            <span class="material-icons text-xs mr-2" id="layer-icon">satellite</span>
                                            <span id="layer-text">Satelit</span>
                                        </button>
                                        <span class="text-xs text-gray-600">Klik pada peta untuk pilih lokasi atau seret marker untuk sesuaikan</span>
                                    </div>
                                    <div class="coordinate-display" id="coordinate-display">
                                        Lat: {{ number_format($kematian->latitude, 6) }}, Lng: {{ number_format($kematian->longitude, 6) }}
                                    </div>
                                </div>
                                
                                <!-- Mobile Layout -->
                                <div class="md:hidden space-y-3">
                                    <!-- Button Row -->
                                    <div class="flex flex-col space-y-2">
                                        <button type="button" onclick="getCurrentLocation()" class="flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700">
                                            <span class="material-icons text-xs mr-2">my_location</span>
                                            Lokasi Semasa
                                        </button>
                                        <button type="button" onclick="resetToDefault()" class="flex items-center justify-center px-3 py-2 bg-gray-600 text-white text-xs rounded-md hover:bg-gray-700">
                                            <span class="material-icons text-xs mr-2">home</span>
                                            Lokasi Default
                                        </button>
                                        <button type="button" id="layer-button-mobile" onclick="switchMapLayer()" class="flex items-center justify-center px-3 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700">
                                            <span class="material-icons text-xs mr-2" id="layer-icon-mobile">satellite</span>
                                            <span id="layer-text-mobile">Satelit</span>
                                        </button>
                                    </div>
                                    
                                    <!-- Instructions -->
                                    <div class="text-center">
                                        <span class="text-xs text-gray-600">Klik pada peta untuk pilih lokasi atau seret marker untuk sesuaikan</span>
                                    </div>
                                    
                                    <!-- Coordinates -->
                                    <div class="coordinate-display text-center" id="coordinate-display-mobile">
                                        Lat: {{ number_format($kematian->latitude, 6) }}, Lng: {{ number_format($kematian->longitude, 6) }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- OpenStreetMap Container -->
                            <div id="map"></div>
                            
                            <p class="text-xs text-gray-500 mt-2">
                                💡 Petua: Klik pada peta untuk meletakkan pin, atau seret marker yang sedia ada untuk sesuaikan lokasi. Peta ini menggunakan OpenStreetMap yang percuma.
                            </p>
                        </div>
                    </div>

                    <!-- Info Waris Section -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                            <span class="material-icons text-green-600 mr-2 text-lg">family_restroom</span>
                            Info Waris
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 form-grid">
                            <!-- Waris -->
                            <div>
                                <label for="waris" class="block text-xs font-medium text-gray-700 mb-2">Waris *</label>
                                <input type="text" id="waris" name="waris" value="{{ old('waris', $kematian->waris) }}" required
                                    class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900 @error('waris') border-b-red-500 @enderror"
                                    placeholder="Contoh: Ali bin Ahmad">
                                @error('waris')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Telefon HP -->
                            <div>
                                <label for="telefon_waris" class="block text-xs font-medium text-gray-700 mb-2">Telefon HP *</label>
                                <input type="text" id="telefon_waris" name="telefon_waris" value="{{ old('telefon_waris', $kematian->telefon_waris) }}" required
                                    class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900 @error('telefon_waris') border-b-red-500 @enderror"
                                    placeholder="Contoh: 012-3456789">
                                @error('telefon_waris')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Catatan & Lampiran -->
                    <div class="bg-white shadow-lg border border-gray-200 p-6 mb-6" x-data="attachmentsEdit({{ $kematian->id }})">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <span class="material-icons text-purple-600 mr-2 text-lg">collections</span>
                                Catatan & Lampiran Gambar
                            </h2>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="catatan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900">{{ old('catatan', $kematian->catatan) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Lampiran Gambar</label>
                                <div class="flex items-center space-x-2 mb-3">
                                    <input type="file" accept="image/*" capture="environment" class="hidden" id="capture-input" @change="capture($event)">
                                    <button type="button" @click="document.getElementById('capture-input').click()" class="px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 flex items-center">
                                        <span class="material-icons text-xs mr-2">photo_camera</span>
                                        Ambil Gambar
                                    </button>
                                    <label class="px-3 py-2 bg-gray-600 text-white text-xs rounded-md hover:bg-gray-700 flex items-center cursor-pointer">
                                        <input type="file" accept="image/*" multiple class="hidden" @change="uploadSelected($event)">
                                        <span class="material-icons text-xs mr-2">upload</span>
                                        Pilih Dari Galeri
                                    </label>
                                </div>

                                <!-- Existing + New thumbnails -->
                                <div class="flex flex-wrap gap-2 items-start">
                                    <template x-for="img in list" :key="img.key">
                                        <div class="relative group flex items-start justify-start">
                                            <img :src="img.url" class="w-16 h-16 object-cover rounded-md border border-gray-200" alt="gambar">
                                            <button type="button" @click="remove(img)" class="absolute top-1 right-1 text-red-500 hover:text-red-600 text-md font-bold cursor-pointer">
                                                ×
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('kematian.index') }}" class="h-8 px-4 flex items-center text-xs text-gray-700 bg-red-100 rounded-xs hover:bg-red-200">
                            Batal
                        </a>
                        <button type="submit" class="h-8 px-4 flex items-center bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700">
                            <span class="material-icons text-[10px] mr-2">save</span>
                            Kemaskini Rekod
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
<script>
function attachmentsEdit(kematianId){
    return {
        list: [
            @foreach($kematian->attachments as $att)
                { key: 'old-{{ $att->id }}', id: {{ $att->id }}, url: '{{ asset('storage/'.$att->path) }}', existing: true },
            @endforeach
        ],
        init() {
            // Initialize attachments
        },
        async uploadFiles(files){
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            for (const file of files){
                const form = new FormData();
                form.append('photo', file);
                const res = await fetch(`/kematian/${kematianId}/attachments`, { method:'POST', headers: { 'X-CSRF-TOKEN': token }, body: form });
                const data = await res.json();
                if (data?.success){
                    const newImage = { key: 'new-'+data.data.id, id: data.data.id, url: data.data.url, existing: true };
                    this.list.unshift(newImage);
                    
                    // Force Alpine.js to update
                    this.$nextTick(() => {
                        // DOM updated
                    });
                }
            }
        },
        capture(e){ this.uploadFiles(e.target.files); e.target.value=''; },
        uploadSelected(e){ this.uploadFiles(e.target.files); e.target.value=''; },
        async remove(img){
            try {
                if (img.existing){
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch(`/kematian/${kematianId}/attachments/${img.id}`, { 
                        method:'DELETE', 
                        headers: { 'X-CSRF-TOKEN': token } 
                    });
                    const data = await res.json();
                    if (!data?.success) {
                        return;
                    }
                }
                
                // Remove from Alpine.js array
                const index = this.list.findIndex(x => x.key === img.key);
                if (index > -1) {
                    this.list.splice(index, 1);
                }
                
                // Force Alpine.js to update
                this.$nextTick(() => {
                    // DOM updated
                });
                
            } catch (error) {
                // Handle error silently
            }
        }
    }
}
</script>
</html>
