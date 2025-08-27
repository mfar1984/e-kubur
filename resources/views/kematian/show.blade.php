<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'E-Kubur - Sistem Pengurusan Jenazah' }}</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        
        .map-info {
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
        
        /* Mobile-specific styles */
        @media (max-width: 640px) {
            .map-info {
                padding: 16px;
            }
            
            .map-info button {
                width: 100%;
                max-width: 200px;
                justify-content: center;
            }
            
            .coordinate-display {
                padding: 12px 16px;
                font-size: 11px;
            }
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
        // Leaflet Maps functionality for show page (read-only)
        let map, marker;
        let currentLayer = 'street'; // Track current layer
        
        function initMap() {
            // Get coordinates from data attributes
            let lat = parseFloat(document.getElementById('map').getAttribute('data-lat')) || {{ \App\Models\Tetapan::getDefaultLatitude() }};
            let lng = parseFloat(document.getElementById('map').getAttribute('data-lng')) || {{ \App\Models\Tetapan::getDefaultLongitude() }};
            
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
            
            // Create marker (not draggable for show page)
            marker = L.marker([lat, lng], {
                icon: customIcon,
                draggable: false
            }).addTo(map);
            
            // Add popup to marker
            marker.bindPopup(`
                <div style="text-align: center;">
                    <strong>Lokasi Kematian</strong><br>
                    <small>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</small>
                </div>
            `);
            
            // Store layers for switching
            map.streetLayer = streetLayer;
            map.satelliteLayer = satelliteLayer;
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Lihat Rekod Kematian</h1>
                        <p class="text-xs text-gray-600">Maklumat terperinci rekod kematian</p>
                    </div>
                </div>

                <!-- Info Orang Meninggal Section -->
                <div class="bg-blue-50 p-6 rounded-lg mb-6">
                    <h2 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                        <span class="material-icons text-blue-600 mr-2 text-lg">person</span>
                        Info Orang Meninggal
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Penuh</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $kematian->nama }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tarikh Lahir</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $kematian->tarikh_lahir_formatted }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nombor IC</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $kematian->no_ic }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tarikh Meninggal</label>
                            <p class="text-xs text-gray-900 font-normal text-red-600">{{ $kematian->tarikh_meninggal_formatted }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Longitude</label>
                            <a href="https://www.google.com/maps?q={{ $kematian->latitude }},{{ $kematian->longitude }}" 
                               target="_blank" 
                               class="text-blue-600 hover:text-blue-800 hover:underline cursor-pointer"
                               title="Klik untuk buka lokasi dalam Google Maps">
                                <p class="text-xs text-gray-900 font-normal flex items-center">
                                    <span class="material-icons text-xs mr-1">location_on</span>
                                    {{ $kematian->longitude }}
                                </p>
                            </a>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Latitude</label>
                            <a href="https://www.google.com/maps?q={{ $kematian->latitude }},{{ $kematian->longitude }}" 
                               target="_blank" 
                               class="text-blue-600 hover:text-blue-800 hover:underline cursor-pointer"
                               title="Klik untuk buka lokasi dalam Google Maps">
                                <p class="text-xs text-gray-900 font-normal flex items-center">
                                    <span class="material-icons text-xs mr-1">location_on</span>
                                    {{ $kematian->latitude }}
                                </p>
                            </a>
                        </div>
                    </div>
                    
                    <!-- OpenStreetMap Section -->
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-blue-900 mb-3 flex items-center">
                            <span class="material-icons text-blue-600 mr-2 text-sm">map</span>
                            Lokasi pada Peta
                        </h3>
                        
                        <!-- Map Info -->
                        <div class="map-info mb-3">
                            <!-- Desktop Layout -->
                            <div class="hidden md:flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="text-xs text-gray-600">Lokasi kematian yang telah direkodkan</span>
                                    <button type="button" id="layer-button" onclick="switchMapLayer()" class="flex items-center px-3 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700">
                                        <span class="material-icons text-xs mr-2" id="layer-icon">satellite</span>
                                        <span id="layer-text">Satelit</span>
                                    </button>
                                </div>
                                <div class="coordinate-display">
                                    <a href="https://www.google.com/maps?q={{ $kematian->latitude }},{{ $kematian->longitude }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-800 hover:underline cursor-pointer"
                                       title="Klik untuk buka lokasi dalam Google Maps">
                                        <span class="material-icons text-xs mr-1">open_in_new</span>
                                        Lat: {{ number_format($kematian->latitude, 6) }}, Lng: {{ number_format($kematian->longitude, 6) }}
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Mobile Layout -->
                            <div class="md:hidden space-y-3">
                                <!-- Instructions -->
                                <div class="text-center">
                                    <span class="text-xs text-gray-600">Lokasi kematian yang telah direkodkan</span>
                                </div>
                                
                                <!-- Button -->
                                <div class="flex justify-center">
                                    <button type="button" id="layer-button-mobile" onclick="switchMapLayer()" class="flex items-center justify-center px-4 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 w-full max-w-xs">
                                        <span class="material-icons text-xs mr-2" id="layer-icon-mobile">satellite</span>
                                        <span id="layer-text-mobile">Satelit</span>
                                    </button>
                                </div>
                                
                                <!-- Coordinates -->
                                <div class="coordinate-display text-center">
                                    <a href="https://www.google.com/maps?q={{ $kematian->latitude }},{{ $kematian->longitude }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-800 hover:underline cursor-pointer"
                                       title="Klik untuk buka lokasi dalam Google Maps">
                                        <span class="material-icons text-xs mr-1">open_in_new</span>
                                        Lat: {{ number_format($kematian->latitude, 6) }}, Lng: {{ number_format($kematian->longitude, 6) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- OpenStreetMap Container -->
                        <div id="map" data-lat="{{ $kematian->latitude }}" data-lng="{{ $kematian->longitude }}"></div>
                        
                        <p class="text-xs text-gray-500 mt-2">
                            💡 Petua: Klik pada marker untuk melihat maklumat koordinat. Peta ini menggunakan OpenStreetMap yang percuma.
                        </p>
                    </div>
                </div>

                <!-- Info Waris Section -->
                <div class="bg-green-50 p-6 rounded-lg mb-6">
                    <h2 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                        <span class="material-icons text-green-600 mr-2 text-lg">family_restroom</span>
                        Info Waris
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Waris</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $kematian->waris }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Telefon HP</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $kematian->telefon_waris }}</p>
                        </div>
                    </div>
                </div>

                <!-- Attachments Section -->
                <div class="bg-white shadow-lg border border-gray-200 p-6 mb-6" x-data="{ open: true }">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <span class="material-icons text-purple-600 mr-2 text-lg">collections</span>
                            Lampiran Gambar
                        </h2>
                        <button @click="open=!open" class="text-gray-600 hover:text-gray-800 p-1 rounded hover:bg-gray-100" title="Toggle Lampiran Gambar">
                            <span class="material-icons text-lg">expand_more</span>
                        </button>
                    </div>
                    <div x-show="open">
                        <div class="flex flex-wrap gap-2 items-start">
                            @forelse($kematian->attachments as $att)
                                <button type="button" class="block flex items-center justify-start hover:bg-gray-50 p-1 rounded" onclick="openImageModal('{{ asset('storage/'.$att->path) }}')">
                                    <img src="{{ asset('storage/'.$att->path) }}" class="w-16 h-16 object-cover rounded-md border border-gray-200" alt="attachment">
                                </button>
                            @empty
                                <p class="text-xs text-gray-500">Tiada lampiran.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="material-icons text-gray-600 mr-2 text-lg">info</span>
                        Maklumat Tambahan
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tarikh Dicipta</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $kematian->created_at_formatted }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tarikh Kemaskini</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $kematian->updated_at_formatted }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('kematian.edit', $kematian) }}" class="h-8 px-4 flex items-center bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700">
                        <span class="material-icons text-[10px] mr-2">edit</span>
                        Edit
                    </a>
                    <button type="button" onclick="showDeleteModal('{{ $kematian->id }}', '{{ $kematian->nama }}')" class="h-8 px-4 flex items-center bg-red-600 text-white text-xs rounded-xs hover:bg-red-700">
                        <span class="material-icons text-[10px] mr-2">delete</span>
                        Padam Rekod
                    </button>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3) !important;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <span class="material-icons text-red-600 text-xl">warning</span>
                </div>
                <div class="mt-4 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Padam Rekod Kematian</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Adakah anda pasti mahu memadamkan rekod kematian untuk <strong id="deleteRecordName"></strong>?
                    </p>
                    <p class="text-xs text-gray-400 mb-4">
                        Tindakan ini tidak boleh dibatalkan. Sila taip kod keselamatan di bawah untuk mengesahkan.
                    </p>
                    <div class="mb-4">
                        <div class="bg-gray-100 p-3 rounded-md mb-3">
                            <span class="text-sm font-mono text-gray-700">Kod Keselamatan: </span>
                            <span id="securityCode" class="text-sm font-mono font-bold text-red-600"></span>
                        </div>
                        <input type="text" id="confirmCode" placeholder="Taip kod keselamatan di atas" 
                               class="w-full px-3 py-2 border border-red-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-gray-900 placeholder-gray-400"
                               maxlength="6" autocomplete="off" inputmode="text">
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="hideDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="confirmDeleteBtn" disabled 
                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            Padam Rekod
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal Viewer -->
    <div id="imageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 hidden">
        <div class="bg-white rounded-lg overflow-hidden shadow-2xl" style="width: 600px !important; max-width: 90vw !important; max-height: 80vh !important;">
            <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200">
                <div class="text-xs text-gray-600">Pratonton Gambar</div>
                <div class="flex items-center gap-2">
                    <button class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200" onclick="zoomOut()">-</button>
                    <button class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200" onclick="resetZoom()">100%</button>
                    <button class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200" onclick="zoomIn()">+</button>
                    <a id="downloadLink" href="#" download class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Muat Turun</a>
                    <button class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700" onclick="closeImageModal()">Tutup</button>
                </div>
            </div>
            <div class="p-3 flex items-center justify-center" style="overflow:auto; max-height:80vh;">
                <img id="modalImage" src="" class="w-full h-auto object-contain" style="width: 100% !important; height: auto !important; object-fit: contain !important; transform: scale(1); transform-origin: center; transition: transform 0.2s ease;" alt="preview">
            </div>
        </div>
    </div>

    <script>
        let currentZoom = 1;

        function openImageModal(src) {
            console.log('Opening modal with:', src);
            const modal = document.getElementById('imageModal');
            const image = document.getElementById('modalImage');
            const downloadLink = document.getElementById('downloadLink');
            
            image.src = src;
            downloadLink.href = src;
            currentZoom = 1;
            image.style.transform = 'scale(1)';
            
            modal.classList.remove('hidden');
            modal.focus();
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            currentZoom = 1;
        }

        function zoomIn() {
            currentZoom = Math.min(currentZoom + 0.25, 5);
            document.getElementById('modalImage').style.transform = `scale(${currentZoom})`;
        }

        function zoomOut() {
            currentZoom = Math.max(currentZoom - 0.25, 0.5);
            document.getElementById('modalImage').style.transform = `scale(${currentZoom})`;
        }

        function resetZoom() {
            currentZoom = 1;
            document.getElementById('modalImage').style.transform = 'scale(1)';
        }

        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Close modal when clicking background
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>

    <script>
        function generateSecurityCode() {
            return Math.random().toString(36).substring(2, 8).toUpperCase();
        }

        function showDeleteModal(recordId, recordName) {
            const modal = document.getElementById('deleteModal');
            const deleteRecordName = document.getElementById('deleteRecordName');
            const securityCode = document.getElementById('securityCode');
            const confirmCode = document.getElementById('confirmCode');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const deleteForm = document.getElementById('deleteForm');
            
            // Set record name
            deleteRecordName.textContent = recordName;
            
            // Generate and display security code
            const code = generateSecurityCode();
            securityCode.textContent = code;
            
            // Set form action
            deleteForm.action = `/kematian/${recordId}`;
            
            // Reset input and button state
            confirmCode.value = '';
            confirmDeleteBtn.disabled = true;
            
            // Show modal
            modal.classList.remove('hidden');
            
            // Focus on input
            confirmCode.focus();
            
            // Check code match on input
            confirmCode.addEventListener('input', function() {
                if (this.value.toUpperCase() === code) {
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    confirmDeleteBtn.disabled = true;
                    confirmDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
            }
        });
    </script>
</body>
</html>
