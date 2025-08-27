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
    <style>
        /* Action button icon styling */
        .action-icon {
            font-size: 16px !important;
            line-height: 1 !important;
        }
        .action-icon .material-icons {
            font-size: 16px !important;
            line-height: 1 !important;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">
    <x-double-navbar :user="$user" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Page Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Daftar Kematian</h1>
                        <p class="text-xs text-gray-600">Senarai rekod kematian yang berdaftar</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('kematian.create') }}" class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700">
                            <span class="material-icons text-[10px] mr-2">person_add</span>
                            Tambah Rekod
                        </a>
                        <a href="{{ route('kematian.export') }}" class="inline-flex items-center justify-center px-3 py-2 bg-green-100 text-gray-700 text-xs rounded-xs hover:bg-green-200">
                            <span class="material-icons text-[10px] mr-2">download</span>
                            Eksport
                        </a>
                    </div>
                </div>

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('kematian.index') }}" class="mb-4 space-y-3 md:space-y-0 md:flex md:space-x-3">
                    <div class="flex-1">
                        <div class="relative">
                            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / nombor IC..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 md:flex md:space-x-2">
                        <div class="relative">
                            <input type="date" name="dari_tarikh" value="{{ request('dari_tarikh') }}" class="w-full py-2 pr-6 pl-3 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 cursor-pointer" placeholder="Dari Tarikh">
                            <span class="material-icons absolute right-1 top-1/2 -translate-y-1/2 text-gray-400 text-sm cursor-pointer" onclick="this.previousElementSibling.focus(); this.previousElementSibling.showPicker();">calendar_today</span>
                        </div>
                        <div class="relative">
                            <input type="date" name="sehingga_tarikh" value="{{ request('sehingga_tarikh') }}" class="w-full py-2 pr-6 pl-3 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 cursor-pointer" placeholder="Sehingga Tarikh">
                            <span class="material-icons absolute right-1 top-1/2 -translate-y-1/2 text-gray-400 text-sm cursor-pointer" onclick="this.previousElementSibling.focus(); this.previousElementSibling.showPicker();">calendar_today</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 md:flex md:space-x-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 flex items-center justify-center">
                            <span class="material-icons text-[10px] mr-1">search</span>
                            Cari
                        </button>
                        <button type="button" onclick="window.location.href='{{ route('kematian.index') }}'" class="px-4 py-2 bg-red-100 text-red-700 text-xs rounded-xs hover:bg-red-200 flex items-center justify-center">
                            <span class="material-icons text-[10px] mr-1">refresh</span>
                            Reset
                        </button>
                    </div>
                </form>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 font-medium text-xs">Nama</th>
                                <th class="px-4 py-2 font-medium text-xs">Umur</th>
                                <th class="px-4 py-2 font-medium text-xs">Tarikh Lahir</th>
                                <th class="px-4 py-2 font-medium text-xs">No. IC</th>
                                <th class="px-4 py-2 font-medium text-xs">Tarikh Meninggal</th>
                                <th class="px-4 py-2 font-medium text-xs">Lokasi</th>
                                <th class="px-4 py-2 font-medium text-xs">Waris</th>
                                <th class="px-4 py-2 font-medium text-xs">Telefon Waris</th>
                                <th class="px-4 py-2 font-medium text-xs text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($kematian as $record)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-1">
                                    <div class="font-medium text-gray-900 text-xs">{{ $record->nama }}</div>
                                </td>
                                <td class="px-4 py-2 text-gray-600 text-xs">{{ $record->umur }} tahun</td>
                                <td class="px-4 py-2 text-gray-600 text-xs">{{ $record->tarikh_lahir_formatted }}</td>
                                <td class="px-4 py-2 text-gray-600 text-xs">{{ $record->no_ic }}</td>
                                <td class="px-4 py-2 text-gray-600 text-xs">{{ $record->tarikh_meninggal_formatted }}</td>
                                <td class="px-4 py-2 text-gray-600 text-xs">
                                    <a href="https://www.google.com/maps?q={{ $record->latitude }},{{ $record->longitude }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-800 hover:underline cursor-pointer"
                                       title="Klik untuk buka lokasi dalam Google Maps">
                                        <div class="text-xs">
                                            <div class="flex items-center">
                                                <span class="material-icons text-xs mr-1">location_on</span>
                                                <span>Lat: {{ $record->latitude }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="material-icons text-xs mr-1">location_on</span>
                                                <span>Lng: {{ $record->longitude }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-4 py-2 text-gray-600 text-xs">{{ $record->waris }}</td>
                                <td class="px-4 py-2 text-gray-600 text-xs">{{ $record->telefon_waris }}</td>
                                <td class="px-4 py-1 text-center space-x-1">
                                    <a href="{{ route('kematian.show', $record) }}" class="text-gray-700 hover:text-gray-900 action-icon" title="Lihat" aria-label="Lihat">
                                        <span class="material-icons text-[8px]">visibility</span>
                                    </a>
                                    <a href="{{ route('kematian.edit', $record) }}" class="text-blue-600 hover:text-blue-800 action-icon" title="Edit" aria-label="Edit">
                                        <span class="material-icons text-[8px]">edit</span>
                                    </a>
                                    <button type="button" onclick="showDeleteModal('{{ $record->id }}', '{{ $record->nama }}')" class="text-red-600 hover:text-red-800 action-icon" title="Padam" aria-label="Padam">
                                        <span class="material-icons text-[8px]">delete</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                    Tiada data kematian dijumpai.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($kematian as $record)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $record->nama }}</h3>
                                <p class="text-xs text-gray-500">{{ $record->umur }} tahun • {{ $record->no_ic }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('kematian.show', $record) }}" class="p-2 text-gray-600 hover:text-gray-800 rounded-full hover:bg-gray-100" title="Lihat">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                <a href="{{ route('kematian.edit', $record) }}" class="p-2 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50" title="Edit">
                                    <span class="material-icons text-sm">edit</span>
                                </a>
                                <button type="button" onclick="showDeleteModal('{{ $record->id }}', '{{ $record->nama }}')" class="p-2 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50" title="Padam">
                                    <span class="material-icons text-sm">delete</span>
                                </button>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500">Tarikh Lahir:</span>
                                <p class="text-gray-900 font-medium">{{ $record->tarikh_lahir_formatted }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Tarikh Meninggal:</span>
                                <p class="text-gray-900 font-medium">{{ $record->tarikh_meninggal_formatted }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Waris:</span>
                                <p class="text-gray-900 font-medium">{{ $record->waris }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Telefon:</span>
                                <p class="text-gray-900 font-medium">{{ $record->telefon_waris }}</p>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <a href="https://www.google.com/maps?q={{ $record->latitude }},{{ $record->longitude }}" 
                               target="_blank" 
                               class="flex items-center text-blue-600 hover:text-blue-800 text-xs">
                                <span class="material-icons text-sm mr-1">location_on</span>
                                <span>Lihat Lokasi di Google Maps</span>
                                <span class="material-icons text-xs ml-1">open_in_new</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">
                        <span class="material-icons text-gray-400 text-4xl mb-2">inbox</span>
                        <p class="text-gray-500 text-sm">Tiada data kematian dijumpai.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($kematian->hasPages())
                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="text-xs text-gray-500 text-center sm:text-left">
                        Menunjukkan {{ $kematian->firstItem() }} hingga {{ $kematian->lastItem() }} daripada {{ $kematian->total() }} rekod
                    </div>
                    <div class="flex justify-center sm:justify-end">
                        {{ $kematian->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
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
