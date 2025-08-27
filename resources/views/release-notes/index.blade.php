<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Nota Keluaran - E-Kubur' }}</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate" x-data="releaseNotes()">
    <x-double-navbar :user="$user" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6 text-center sm:text-left">
                    <h1 class="text-xl font-bold text-gray-900 mb-2">Nota Keluaran</h1>
                    <p class="text-xs text-gray-600">Sejarah kemaskini dan pembangunan Sistem E-Kubur</p>
                </div>

                <!-- Current Version Banner -->
                <div class="mb-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xs p-6 text-white">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-center sm:text-left mb-4 sm:mb-0">
                            <div class="flex items-center justify-center sm:justify-start mb-2">
                                <span class="material-icons text-2xl mr-2">new_releases</span>
                                <h2 class="text-lg font-bold">Versi Semasa</h2>
                            </div>
                            <div class="text-2xl font-bold mb-1">v2.5.0</div>
                            <p class="text-sm opacity-90">Sistem Maklum Balas Awam dengan reCAPTCHA & Pengesahan Emel</p>
                        </div>
                        <div class="text-center sm:text-right">
                                                                <div class="text-sm opacity-90 mb-1">Dikeluarkan pada</div>
                                    <div class="text-lg font-semibold">25/12/2024</div>
                        </div>
                    </div>
                </div>

                <!-- Version Filter -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                        <button @click="filterType = 'all'" 
                                :class="filterType === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-3 py-1 rounded-full text-xs font-medium transition-colors">
                            Semua Versi
                        </button>
                        <button @click="filterType = 'major'" 
                                :class="filterType === 'major' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-3 py-1 rounded-full text-xs font-medium transition-colors">
                            Major
                        </button>
                        <button @click="filterType = 'minor'" 
                                :class="filterType === 'minor' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-3 py-1 rounded-full text-xs font-medium transition-colors">
                            Minor
                        </button>
                        <button @click="filterType = 'initial'" 
                                :class="filterType === 'initial' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-3 py-1 rounded-full text-xs font-medium transition-colors">
                            Initial
                        </button>
                    </div>
                </div>

                <!-- Release Notes -->
                <div class="space-y-6">
                    @foreach($releases as $releaseIndex => $release)
                    <div x-show="shouldShowRelease('{{ $release['type'] }}')" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white border border-gray-200 rounded-xs overflow-hidden">
                        
                        <!-- Release Header -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                                <div class="flex items-center mb-3 sm:mb-0">
                                    <div class="w-10 h-10 rounded-full 
                                        @if($release['type'] === 'major') bg-red-100 text-red-600
                                        @elseif($release['type'] === 'minor') bg-yellow-100 text-yellow-600
                                        @elseif($release['type'] === 'initial') bg-green-100 text-green-600
                                        @endif
                                        flex items-center justify-center mr-3">
                                        <span class="material-icons text-lg">
                                            @if($release['type'] === 'major') new_releases
                                            @elseif($release['type'] === 'minor') update
                                            @elseif($release['type'] === 'initial') rocket_launch
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $release['version'] }}</h3>
                                        <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($release['date'])->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($release['type'] === 'major') bg-red-100 text-red-800
                                        @elseif($release['type'] === 'minor') bg-yellow-100 text-yellow-800
                                        @elseif($release['type'] === 'initial') bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($release['type']) }}
                                    </span>
                                </div>
                            </div>
                            
                            <h4 class="text-md font-semibold text-gray-900 mb-2">{{ $release['title'] }}</h4>
                            <p class="text-xs text-gray-600 mb-4">{{ $release['description'] }}</p>
                            
                            <!-- Highlights -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($release['highlights'] as $highlight)
                                <div class="flex items-center text-xs">
                                    <span class="mr-2">{{ $highlight }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Release Content -->
                        <div class="p-6">
                            <!-- Features -->
                            <div class="space-y-6">
                                @foreach($release['features'] as $feature)
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                        <span class="material-icons text-sm mr-2 text-blue-600">category</span>
                                        {{ $feature['category'] }}
                                    </h5>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($feature['items'] as $item)
                                        <div class="flex items-start">
                                            <span class="material-icons text-xs text-green-600 mr-2 mt-0.5">check_circle</span>
                                            <span class="text-xs text-gray-700">{{ $item }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Technical Details -->
                            @if(isset($release['technical']))
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h5 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <span class="material-icons text-sm mr-2 text-purple-600">build</span>
                                    Maklumat Teknikal
                                </h5>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($release['technical'] as $tech)
                                    <div class="flex items-center text-xs">
                                        <span class="material-icons text-xs mr-2 text-purple-600">code</span>
                                        <span class="text-gray-700">{{ $tech }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- No Results Message -->
                <div x-show="!hasVisibleReleases()" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="text-center py-12">
                    <div class="text-gray-500">
                        <span class="material-icons text-4xl mb-2 block">search_off</span>
                        <p class="text-sm">Tiada versi yang sepadan dengan penapis yang dipilih.</p>
                        <p class="text-xs mt-1">Cuba pilih penapis yang berbeza.</p>
                    </div>
                </div>

                <!-- Version History Summary -->
                <div class="mt-8 bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center mx-auto mb-3">
                            <span class="material-icons text-xl">timeline</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Ringkasan Perkembangan</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ count($releases) }}</div>
                                <div class="text-xs text-gray-600">Jumlah Versi</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ collect($releases)->where('type', 'major')->count() }}</div>
                                <div class="text-xs text-gray-600">Major Releases</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ \Carbon\Carbon::parse($releases[0]['date'])->diffInDays(\Carbon\Carbon::parse($releases[count($releases)-1]['date'])) }}</div>
                                <div class="text-xs text-gray-600">Hari Pembangunan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feedback Section -->
                <div class="mt-8 bg-blue-50 rounded-xs border border-blue-200 p-6">
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3">
                            <span class="material-icons text-xl">feedback</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Maklum Balas & Cadangan</h3>
                        <p class="text-xs text-gray-600 mb-4">Kami sentiasa terbuka untuk maklum balas dan cadangan untuk meningkatkan sistem.</p>
                        <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                            <button class="px-4 py-3 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 transition-colors flex items-center justify-center">
                                <span class="material-icons text-xs mr-2">bug_report</span>
                                Laporkan Bug
                            </button>
                            <button class="px-4 py-3 bg-green-600 text-white text-xs rounded-xs hover:bg-green-700 transition-colors flex items-center justify-center">
                                <span class="material-icons text-xs mr-2">lightbulb</span>
                                Cadangan Fitur
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function releaseNotes() {
            return {
                filterType: 'all',
                
                shouldShowRelease(type) {
                    if (this.filterType === 'all') return true;
                    return this.filterType === type;
                },
                
                hasVisibleReleases() {
                    const releases = document.querySelectorAll('[x-show*="shouldShowRelease"]');
                    return Array.from(releases).some(el => el.style.display !== 'none');
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbar */
        .release-content {
            scrollbar-width: thin;
            scrollbar-color: #CBD5E0 #F7FAFC;
        }
        
        .release-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .release-content::-webkit-scrollbar-track {
            background: #F7FAFC;
        }
        
        .release-content::-webkit-scrollbar-thumb {
            background: #CBD5E0;
            border-radius: 3px;
        }
        
        .release-content::-webkit-scrollbar-thumb:hover {
            background: #A0AEC0;
        }

        /* Gradient animation for current version banner */
        .bg-gradient-to-r {
            background-size: 200% 200%;
            animation: gradient 3s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</body>
</html>
