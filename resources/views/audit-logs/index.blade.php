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
                        <h1 class="text-xl font-semibold text-gray-900 mb-1">Log Audit & Keselamatan</h1>
                        <p class="text-xs text-gray-600">Rekod aktiviti sistem dan keselamatan</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="/audit-logs-export" class="inline-flex items-center justify-center px-3 py-2 bg-green-100 text-green-700 text-xs rounded-xs hover:bg-green-200">
                            <span class="material-icons text-[10px] mr-2">download</span>
                            Eksport
                        </a>
                        <button onclick="showClearLogsModal()" class="inline-flex items-center justify-center px-3 py-2 bg-red-100 text-red-700 text-xs rounded-xs hover:bg-red-200">
                            <span class="material-icons text-[10px] mr-2">delete_sweep</span>
                            Padam Log Lama
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('audit-logs.index') }}" class="mb-4 space-y-3 md:space-y-0">
                    <div class="flex flex-col lg:flex-row lg:space-x-3 space-y-3 lg:space-y-0">
                        <div class="flex-1">
                            <div class="relative">
                                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">search</span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktiviti, pengguna, atau keterangan..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 lg:flex lg:space-x-3 gap-2 lg:gap-0">
                            <select name="event_type" class="px-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                <option value="">Semua Event</option>
                                @foreach($eventTypes as $type)
                                    <option value="{{ $type }}" {{ request('event_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            <select name="event_category" class="px-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                <option value="">Semua Kategori</option>
                                @foreach($eventCategories as $category)
                                    <option value="{{ $category }}" {{ request('event_category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 lg:flex lg:space-x-3 gap-2 lg:gap-0">
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900" placeholder="Dari Tarikh" onclick="this.showPicker()">
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900" placeholder="Sehingga Tarikh" onclick="this.showPicker()">
                        </div>
                        <div class="grid grid-cols-2 lg:flex lg:space-x-3 gap-2 lg:gap-0">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 flex items-center justify-center h-8">
                                <span class="material-icons text-[10px] mr-1">search</span>
                                Cari
                            </button>
                            <button type="button" onclick="window.location.href='{{ route('audit-logs.index') }}'" class="px-4 py-2 bg-red-100 text-red-700 text-xs rounded-xs hover:bg-red-200 flex items-center justify-center h-8">
                                <span class="material-icons text-[10px] mr-1">refresh</span>
                                Reset
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Desktop Table -->
                <div class="hidden lg:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 font-semibold text-xs">Aktiviti</th>
                                <th class="px-4 py-2 font-semibold text-xs">Kategori</th>
                                <th class="px-4 py-2 font-semibold text-xs">Pengguna</th>
                                <th class="px-4 py-2 font-semibold text-xs">Model</th>
                                <th class="px-4 py-2 font-semibold text-xs">IP Address</th>
                                <th class="px-4 py-2 font-semibold text-xs">Masa</th>
                                <th class="px-4 py-2 font-semibold text-xs text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($activities as $activity)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-2">
                                    <div class="font-semibold text-gray-900 text-xs">
                                        @if($activity->event === 'created')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="material-icons text-[8px] mr-1">add</span>
                                                Dicipta
                                            </span>
                                        @elseif($activity->event === 'updated')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <span class="material-icons text-[8px] mr-1">edit</span>
                                                Dikemaskini
                                            </span>
                                        @elseif($activity->event === 'deleted')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="material-icons text-[8px] mr-1">delete</span>
                                                Dipadamkan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="material-icons text-[8px] mr-1">info</span>
                                                {{ ucfirst($activity->event) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-gray-900 text-xs">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $activity->log_name ?? 'System' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-gray-900 text-xs">
                                    @if($activity->causer)
                                        <div class="font-medium">{{ $activity->causer->name }}</div>
                                        <div class="text-gray-500 text-xs">{{ $activity->causer->email }}</div>
                                    @else
                                        <span class="text-gray-400">System</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-900 text-xs">
                                    @if($activity->subject_type)
                                        @php
                                            $modelName = class_basename($activity->subject_type);
                                            $modelDisplayName = match($modelName) {
                                                'User' => 'Pengguna',
                                                'Tetapan' => 'Tetapan',
                                                'Kematian' => 'Kematian',
                                                'Ppjub' => 'PPJUB',
                                                'Integration' => 'Integrasi',
                                                'Role' => 'Kumpulan Akses',
                                                'Permission' => 'Izin',
                                                default => $modelName
                                            };
                                        @endphp
                                        <div class="font-medium">{{ $modelDisplayName }}</div>
                                        @if($activity->subject)
                                            @if($activity->subject_type === 'App\Models\User')
                                                <div class="text-gray-500 text-xs">{{ $activity->subject->name }}</div>
                                            @elseif($activity->subject_type === 'App\Models\Tetapan')
                                                <div class="text-gray-500 text-xs">{{ $activity->subject->nama }}</div>
                                            @elseif($activity->subject_type === 'App\Models\Kematian')
                                                <div class="text-gray-500 text-xs">{{ $activity->subject->nama }}</div>
                                            @elseif($activity->subject_type === 'App\Models\Ppjub')
                                                <div class="text-gray-500 text-xs">{{ $activity->subject->nama }}</div>
                                            @elseif($activity->subject_type === 'App\Models\Integration')
                                                <div class="text-gray-500 text-xs">{{ $activity->subject->nama }}</div>
                                            @elseif($activity->subject_type === 'Spatie\Permission\Models\Role')
                                                <div class="text-gray-500 text-xs">{{ $activity->subject->name }}</div>
                                            @elseif($activity->subject_type === 'Spatie\Permission\Models\Permission')
                                                <div class="text-gray-500 text-xs">{{ $activity->subject->name }}</div>
                                            @else
                                                <div class="text-gray-500 text-xs">ID: {{ $activity->subject_id }}</div>
                                            @endif
                                        @else
                                            <div class="text-gray-500 text-xs">ID: {{ $activity->subject_id }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-900 text-xs">
                                    @if($activity->properties->get('ip_address'))
                                        <div class="font-mono text-xs">{{ $activity->properties->get('ip_address') }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-900 text-xs">
                                    <div class="font-medium">{{ $activity->created_at->format('d/m/Y') }}</div>
                                    <div class="text-gray-500 text-xs">{{ $activity->created_at->format('H:i:s') }}</div>
                                    <div class="text-gray-400 text-xs">{{ $activity->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-4 py-1 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('audit-logs.show', $activity) }}" class="text-gray-700 hover:text-gray-900 action-icon" title="Lihat" aria-label="Lihat">
                                            <span class="material-icons text-[8px]">visibility</span>
                                        </a>
                                        <button type="button" onclick="showDeleteModal('{{ $activity->id }}', 'Log #{{ $activity->id }}')" class="text-red-600 hover:text-red-800 action-icon" title="Padam" aria-label="Padam">
                                            <span class="material-icons text-[8px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <div class="text-gray-500">
                                        <span class="material-icons text-4xl mb-2 block">security</span>
                                        <p class="text-sm">Tiada log audit dijumpai</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden space-y-3">
                    @forelse($activities as $activity)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Activity and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 text-xs mb-1">
                                    @if($activity->event === 'created')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="material-icons text-[8px] mr-1">add</span>
                                            Dicipta
                                        </span>
                                    @elseif($activity->event === 'updated')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <span class="material-icons text-[8px] mr-1">edit</span>
                                            Dikemaskini
                                        </span>
                                    @elseif($activity->event === 'deleted')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="material-icons text-[8px] mr-1">delete</span>
                                            Dipadamkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <span class="material-icons text-[8px] mr-1">info</span>
                                            {{ ucfirst($activity->event) }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500">{{ $activity->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('audit-logs.show', $activity) }}" class="p-2 text-gray-600 hover:text-gray-800 rounded-full hover:bg-gray-100" title="Lihat">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                <button type="button" onclick="showDeleteModal('{{ $activity->id }}', 'Log #{{ $activity->id }}')" class="p-2 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50" title="Padam">
                                    <span class="material-icons text-sm">delete</span>
                                </button>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500">Kategori:</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $activity->log_name ?? 'System' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500">Pengguna:</span>
                                @if($activity->causer)
                                    <p class="text-gray-900 font-medium">{{ $activity->causer->name }}</p>
                                @else
                                    <span class="text-gray-400">System</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-gray-500">Model:</span>
                                @if($activity->subject_type)
                                    @php
                                        $modelName = class_basename($activity->subject_type);
                                        $modelDisplayName = match($modelName) {
                                            'User' => 'Pengguna',
                                            'Tetapan' => 'Tetapan',
                                            'Kematian' => 'Kematian',
                                            'Ppjub' => 'PPJUB',
                                            'Integration' => 'Integrasi',
                                            'Role' => 'Kumpulan Akses',
                                            'Permission' => 'Izin',
                                            default => $modelName
                                        };
                                    @endphp
                                    <p class="text-gray-900 font-medium">{{ $modelDisplayName }}</p>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-gray-500">IP Address:</span>
                                @if($activity->properties->get('ip_address'))
                                    <p class="text-gray-900 font-medium font-mono">{{ $activity->properties->get('ip_address') }}</p>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">
                        <span class="material-icons text-gray-400 text-4xl mb-2">security</span>
                        <p class="text-gray-500 text-sm">Tiada data log audit dijumpai.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($activities->hasPages())
                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="text-xs text-gray-500 text-center sm:text-left">
                        Menunjukkan {{ $activities->firstItem() }} hingga {{ $activities->lastItem() }} daripada {{ $activities->total() }} rekod
                    </div>
                    <div class="flex justify-center sm:justify-end">
                        {{ $activities->appends(request()->query())->links('pagination::simple-tailwind') }}
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Padam Log Audit</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Adakah anda pasti mahu memadamkan <strong id="deleteRecordName"></strong>?
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
                            Padam Log
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Clear Logs Modal -->
    <div id="clearLogsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Padam Log Lama</h3>
                <p class="text-sm text-gray-600 mb-4">Pilih berapa hari log lama yang hendak dipadamkan:</p>
                
                <form action="{{ route('audit-logs.clear') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <select name="days" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="30">30 hari</option>
                            <option value="60">60 hari</option>
                            <option value="90" selected>90 hari</option>
                            <option value="180">180 hari</option>
                            <option value="365">1 tahun</option>
                        </select>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="button" onclick="hideClearLogsModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-xs hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white text-sm rounded-xs hover:bg-red-700">
                            Padam
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 18px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
        
        .action-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 3px;
            transition: all 0.2s;
        }
        
        .action-icon:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
    </style>

    <script>
        // Delete Modal Functions
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
            deleteForm.action = `/audit-logs/${recordId}`;
            
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

        // Close delete modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });

        // Close delete modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
                hideClearLogsModal();
            }
        });

        // Clear Logs Modal Functions
        function showClearLogsModal() {
            document.getElementById('clearLogsModal').classList.remove('hidden');
        }
        
        function hideClearLogsModal() {
            document.getElementById('clearLogsModal').classList.add('hidden');
        }
        
        // Close clear logs modal when clicking outside
        document.getElementById('clearLogsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideClearLogsModal();
            }
        });
    </script>
</body>
</html>
