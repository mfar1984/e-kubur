<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Panduan Pengguna - E-Kubur' }}</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate" x-data="userGuide()">
    <x-double-navbar :user="$user" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6 text-center sm:text-left">
                    <h1 class="text-xl font-bold text-gray-900 mb-2">Panduan Pengguna</h1>
                    <p class="text-xs text-gray-600">Panduan lengkap untuk menggunakan Sistem E-Kubur dengan berkesan</p>
                </div>

                <!-- Table of Contents -->
                <div class="mb-8 bg-blue-50 rounded-xs border border-blue-200 p-4">
                    <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                        <span class="material-icons text-sm mr-2 text-blue-600">list</span>
                        Isi Kandungan
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($guides as $index => $guide)
                        <button @click="scrollToSection({{ $index }})" 
                                class="text-left p-2 rounded hover:bg-blue-100 transition-colors flex items-center">
                            <span class="material-icons text-xs mr-2 
                                @if($guide['color'] === 'blue') text-blue-600
                                @elseif($guide['color'] === 'red') text-red-600
                                @elseif($guide['color'] === 'purple') text-purple-600
                                @elseif($guide['color'] === 'orange') text-orange-600
                                @elseif($guide['color'] === 'green') text-green-600
                                @elseif($guide['color'] === 'indigo') text-indigo-600
                                @endif">{{ $guide['icon'] }}</span>
                            <span class="text-xs text-gray-700">{{ $guide['title'] }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Guide Sections -->
                <div class="space-y-8">
                    @foreach($guides as $guideIndex => $guide)
                    <div id="guide-{{ $guideIndex }}" class="scroll-mt-8">
                        <!-- Guide Header -->
                        <div class="flex items-center mb-6 p-4 bg-gray-50 rounded-xs border border-gray-200">
                            <div class="w-12 h-12 rounded-full 
                                @if($guide['color'] === 'blue') bg-blue-100 text-blue-600
                                @elseif($guide['color'] === 'red') bg-red-100 text-red-600
                                @elseif($guide['color'] === 'purple') bg-purple-100 text-purple-600
                                @elseif($guide['color'] === 'orange') bg-orange-100 text-orange-600
                                @elseif($guide['color'] === 'green') bg-green-100 text-green-600
                                @elseif($guide['color'] === 'indigo') bg-indigo-100 text-indigo-600
                                @endif
                                flex items-center justify-center mr-4">
                                <span class="material-icons text-xl">{{ $guide['icon'] }}</span>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">{{ $guide['title'] }}</h2>
                                <p class="text-xs text-gray-600">Panduan lengkap untuk {{ strtolower($guide['title']) }}</p>
                            </div>
                        </div>

                        <!-- Guide Sections -->
                        <div class="space-y-6">
                            @foreach($guide['sections'] as $sectionIndex => $section)
                            <div class="bg-white border border-gray-200 rounded-xs overflow-hidden">
                                <!-- Section Header -->
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                        <span class="material-icons text-sm mr-2 text-gray-600">arrow_right</span>
                                        {{ $section['subtitle'] }}
                                    </h3>
                                </div>
                                
                                <!-- Steps -->
                                <div class="p-4">
                                    <div class="space-y-3">
                                        @foreach($section['steps'] as $stepIndex => $step)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-6 h-6 rounded-full 
                                                @if($guide['color'] === 'blue') bg-blue-100 text-blue-600
                                                @elseif($guide['color'] === 'red') bg-red-100 text-red-600
                                                @elseif($guide['color'] === 'purple') bg-purple-100 text-purple-600
                                                @elseif($guide['color'] === 'orange') bg-orange-100 text-orange-600
                                                @elseif($guide['color'] === 'green') bg-green-100 text-green-600
                                                @elseif($guide['color'] === 'indigo') bg-indigo-100 text-indigo-600
                                                @endif
                                                flex items-center justify-center mr-3 mt-0.5">
                                                <span class="text-xs font-semibold">{{ $stepIndex + 1 }}</span>
                                            </div>
                                            <p class="text-xs text-gray-700 leading-relaxed flex-1">{{ $step }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Quick Tips Section -->
                <div class="mt-8 bg-yellow-50 rounded-xs border border-yellow-200 p-6">
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mx-auto mb-3">
                            <span class="material-icons text-xl">lightbulb</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Petua Pantas</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                            <div class="text-left">
                                <h4 class="text-xs font-semibold text-gray-900 mb-1">💡 Gunakan Aplikasi Pantas</h4>
                                <p class="text-xs text-gray-600">Klik ikon grid untuk akses pantas ke semua modul</p>
                            </div>
                            <div class="text-left">
                                <h4 class="text-xs font-semibold text-gray-900 mb-1">🔍 Fungsi Carian</h4>
                                <p class="text-xs text-gray-600">Gunakan kotak carian untuk mencari rekod dengan cepat</p>
                            </div>
                            <div class="text-left">
                                <h4 class="text-xs font-semibold text-gray-900 mb-1">📱 Responsif</h4>
                                <p class="text-xs text-gray-600">Sistem berfungsi dengan baik pada desktop dan mobile</p>
                            </div>
                            <div class="text-left">
                                <h4 class="text-xs font-semibold text-gray-900 mb-1">🗺️ Peta Interaktif</h4>
                                <p class="text-xs text-gray-600">Klik koordinat untuk membuka Google Maps</p>
                            </div>
                            <div class="text-left">
                                <h4 class="text-xs font-semibold text-gray-900 mb-1">📊 Eksport Data</h4>
                                <p class="text-xs text-gray-600">Eksport data untuk backup dan analisis</p>
                            </div>
                            <div class="text-left">
                                <h4 class="text-xs font-semibold text-gray-900 mb-1">🔒 Keselamatan</h4>
                                <p class="text-xs text-gray-600">Semua aktiviti direkodkan dalam log audit</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Section -->
                <div class="mt-8 bg-blue-50 rounded-xs border border-blue-200 p-6">
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3">
                            <span class="material-icons text-xl">support_agent</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Perlukan Bantuan?</h3>
                        <p class="text-xs text-gray-600 mb-4">Jika anda masih memerlukan bantuan, sila rujuk sumber berikut:</p>
                        <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                            <a href="{{ route('faq.index') }}" class="px-4 py-3 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 transition-colors flex items-center justify-center">
                                <span class="material-icons text-xs mr-2">help</span>
                                Soalan Lazim (FAQ)
                            </a>
                            <button class="px-4 py-3 bg-green-600 text-white text-xs rounded-xs hover:bg-green-700 transition-colors flex items-center justify-center">
                                <span class="material-icons text-xs mr-2">email</span>
                                Hubungi Sokongan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function userGuide() {
            return {
                scrollToSection(index) {
                    const element = document.getElementById(`guide-${index}`);
                    if (element) {
                        element.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbar */
        .guide-content {
            scrollbar-width: thin;
            scrollbar-color: #CBD5E0 #F7FAFC;
        }
        
        .guide-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .guide-content::-webkit-scrollbar-track {
            background: #F7FAFC;
        }
        
        .guide-content::-webkit-scrollbar-thumb {
            background: #CBD5E0;
            border-radius: 3px;
        }
        
        .guide-content::-webkit-scrollbar-thumb:hover {
            background: #A0AEC0;
        }

        /* Smooth scrolling for the whole page */
        html {
            scroll-behavior: smooth;
        }
    </style>
</body>
</html>
