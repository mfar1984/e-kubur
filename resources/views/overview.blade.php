<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'E-Kubur - Sistem Pengurusan Jenazah' }}</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">
    <x-double-navbar :user="$user" />
    
    <div class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Dashboard Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-2">Dashboard</h1>
                    <p class="text-xs text-gray-600">Selamat datang ke Sistem Pengurusan Jenazah</p>
                </div>

            <!-- Stats Cards Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Ahli PPJUB -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <span class="material-icons text-xl">group</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Ahli PPJUB</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalPpjub }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        @if($ppjubGrowth >= 0)
                            <span class="text-green-600 flex items-center">
                                <span class="material-icons text-sm mr-1">trending_up</span>
                                +{{ $ppjubGrowth }}%
                            </span>
                        @else
                            <span class="text-red-600 flex items-center">
                                <span class="material-icons text-sm mr-1">trending_down</span>
                                {{ $ppjubGrowth }}%
                            </span>
                        @endif
                        <span class="text-gray-500 ml-2">dari bulan lepas</span>
                    </div>
                </div>

                <!-- Total Rekod Kematian -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                            <span class="material-icons text-xl">person_off</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Rekod Kematian</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalKematian }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        @if($kematianGrowth >= 0)
                            <span class="text-green-600 flex items-center">
                                <span class="material-icons text-sm mr-1">trending_up</span>
                                +{{ $kematianGrowth }}%
                            </span>
                        @else
                            <span class="text-red-600 flex items-center">
                                <span class="material-icons text-sm mr-1">trending_down</span>
                                {{ $kematianGrowth }}%
                            </span>
                        @endif
                        <span class="text-gray-500 ml-2">dari bulan lepas</span>
                    </div>
                </div>

                <!-- Total Waris -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                            <span class="material-icons text-xl">family_restroom</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Waris</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalWaris }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-blue-600 flex items-center">
                            <span class="material-icons text-sm mr-1">people</span>
                            {{ $newWarisThisMonth }} waris baru bulan ini
                        </span>
                    </div>
                </div>

                <!-- Status Perkhidmatan -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                            <span class="material-icons text-xl">local_hospital</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Status Perkhidmatan</p>
                            <p class="text-xl font-bold text-gray-900">{{ $serviceStatus }}%</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 flex items-center">
                            <span class="material-icons text-sm mr-1">check_circle</span>
                            Aktif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Kematian Trend -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend Kematian Bulanan</h3>
                    <div class="relative h-64">
                        <canvas id="donationChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <!-- Ahli PPJUB by Status -->
                <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ahli PPJUB mengikut Status</h3>
                    <div class="relative h-64">
                        <canvas id="zoneChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>

                <!-- Activities & Events Tab View -->
                <div class="bg-gray-50 rounded-xs border border-gray-200" x-data="{ activeTab: 'activities' }">
                <!-- Tab Headers -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button @click="activeTab = 'activities'" 
                                :class="activeTab === 'activities' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            <span class="flex items-center">
                                <span class="material-icons text-sm mr-2">history</span>
                                Daftar Kematian
                            </span>
                        </button>
                        <button @click="activeTab = 'events'" 
                                :class="activeTab === 'events' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            <span class="flex items-center">
                                <span class="material-icons text-sm mr-2">group</span>
                                Ahli PPJUB
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Daftar Kematian Tab -->
                    <div x-show="activeTab === 'activities'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="space-y-4">
                            @forelse($recentDeaths as $death)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-red-600 text-sm">person_off</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $death->nama }}</p>
                                    <p class="text-sm text-gray-500">Tarikh Meninggal: {{ \Carbon\Carbon::parse($death->tarikh_meninggal)->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-500">Waris: {{ $death->waris }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($death->tarikh_meninggal)->diffForHumans() }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <span class="material-icons text-gray-400 text-4xl mb-2">inbox</span>
                                <p class="text-gray-500">Tiada rekod kematian</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Perkhidmatan Terkini Tab -->
                    <div x-show="activeTab === 'events'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="space-y-4">
                            @forelse($recentPpjubActivities as $ppjub)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-blue-600 text-sm">group</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $ppjub->nama }}</p>
                                    <p class="text-sm text-gray-500">Status: {{ $ppjub->status }}</p>
                                    <p class="text-sm text-gray-500">Email: {{ $ppjub->email }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $ppjub->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <span class="material-icons text-gray-400 text-4xl mb-2">group</span>
                                <p class="text-gray-500">Tiada ahli PPJUB</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        // Wait for DOM to be ready and Chart.js to load
        document.addEventListener('DOMContentLoaded', function() {
            // Set timeout to prevent hanging
            const chartTimeout = setTimeout(() => {
                console.warn('Chart.js loading timeout, showing fallback content');
                showChartFallbacks();
            }, 5000); // 5 second timeout

            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.warn('Chart.js not loaded, showing fallback content');
                clearTimeout(chartTimeout);
                showChartFallbacks();
                return;
            }

            try {
                // Clear timeout since charts are loading successfully
                clearTimeout(chartTimeout);
                
                // Donation Trend Chart
                const donationCtx = document.getElementById('donationChart');
                if (donationCtx) {
                    const donationChart = new Chart(donationCtx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: @json(collect($monthlyDeaths)->pluck('month')),
                            datasets: [{
                                label: 'Kematian (Bilangan)',
                                data: @json(collect($monthlyDeaths)->pluck('count')),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString() + ' kes';
                                        }
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    radius: 4,
                                    hoverRadius: 6
                                }
                            }
                        }
                    });
                }

                // Zone Chart
                const zoneCtx = document.getElementById('zoneChart');
                if (zoneCtx) {
                    const zoneChart = new Chart(zoneCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: @json(array_keys($ppjubStatus)),
                            datasets: [{
                                data: @json(array_values($ppjubStatus)),
                                backgroundColor: [
                                    'rgb(16, 185, 129)', // Green for Aktif
                                    'rgb(239, 68, 68)'   // Red for Tidak Aktif
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error creating charts:', error);
                showChartFallbacks();
            }
        });

        // Fallback function if charts fail to load
        function showChartFallbacks() {
            const donationChart = document.getElementById('donationChart');
            const zoneChart = document.getElementById('zoneChart');
            
            if (donationChart) {
                donationChart.innerHTML = `
                    <div class="flex items-center justify-center h-full text-gray-500">
                        <div class="text-center">
                            <span class="material-icons text-4xl mb-2">bar_chart</span>
                                                    <p class="text-sm">Trend Kematian Bulanan</p>
                        <p class="text-xs text-gray-400">Data real dari database</p>
                        </div>
                    </div>
                `;
            }
            
            if (zoneChart) {
                zoneChart.innerHTML = `
                    <div class="flex items-center justify-center h-full text-gray-500">
                        <div class="text-center">
                            <span class="material-icons text-4xl mb-2">pie_chart</span>
                            <p class="text-sm">Ahli PPJUB mengikut Status</p>
                            <p class="text-xs text-gray-400">Data real dari database</p>
                        </div>
                    </div>
                `;
            }
        }
    </script>
</body>
</html> 