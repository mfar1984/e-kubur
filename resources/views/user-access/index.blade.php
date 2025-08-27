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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Pengguna Akses</h1>
                        <p class="text-xs text-gray-600">Senarai pengguna akses yang berdaftar dalam sistem</p>
                    </div>
                    <div class="flex items-center justify-center md:justify-end">
                        <a href="{{ route('user-access.create') }}" class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700">
                            <span class="material-icons text-[10px] mr-2">person_add</span>
                            Tambah Pengguna
                        </a>
                    </div>
                </div>

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('user-access.index') }}" class="mb-4 space-y-3 md:space-y-0 md:flex md:space-x-3">
                    <div class="flex-1">
                        <div class="relative">
                            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900" />
                        </div>
                    </div>
                    <div class="w-full md:w-48">
                        <select name="role" class="w-full px-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                            <option value="">Semua Kumpulan</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2 md:flex md:space-x-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 flex items-center justify-center">
                            <span class="material-icons text-[10px] mr-1">search</span>
                            Cari
                        </button>
                        <button type="button" onclick="window.location.href='{{ route('user-access.index') }}'" class="px-4 py-2 bg-red-100 text-red-700 text-xs rounded-xs hover:bg-red-200 flex items-center justify-center">
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
                                <th class="px-4 py-2 font-medium text-xs">Email</th>
                                <th class="px-4 py-2 font-medium text-xs">Telefon</th>
                                <th class="px-4 py-2 font-medium text-xs">Kumpulan Akses</th>
                                <th class="px-4 py-2 font-medium text-xs">Tarikh Cipta</th>
                                <th class="px-4 py-2 font-medium text-xs text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-1">
                                    <div class="font-medium text-gray-600 text-xs">{{ $user->name }}</div>
                                </td>
                                <td class="px-4 py-2 text-gray-600 text-xs">{{ $user->email }}</td>
                                <td class="px-4 py-2 text-gray-600 text-xs">{{ $user->phone }}</td>
                                <td class="px-4 py-2 text-gray-600 text-xs">
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-600 mr-1">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-600">Tiada kumpulan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-600 text-xs">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-1 text-center space-x-1">
                                    <a href="{{ route('user-access.show', $user) }}" class="text-gray-600 hover:text-gray-900 action-icon" title="Lihat" aria-label="Lihat">
                                        <span class="material-icons text-[8px]">visibility</span>
                                    </a>
                                    <a href="{{ route('user-access.edit', $user) }}" class="text-blue-600 hover:text-blue-800 action-icon" title="Edit" aria-label="Edit">
                                        <span class="material-icons text-[8px]">edit</span>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <button type="button" onclick="showDeleteModal('{{ $user->id }}', '{{ $user->name }}')" class="text-red-600 hover:text-red-600 action-icon" title="Padam" aria-label="Padam">
                                        <span class="material-icons text-[8px]">delete</span>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="text-gray-500">
                                        <span class="material-icons text-4xl mb-2 block">person</span>
                                        <p class="text-sm">Tiada pengguna akses dijumpai</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($users as $user)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $user->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('user-access.show', $user) }}" class="p-2 text-gray-600 hover:text-gray-800 rounded-full hover:bg-gray-100" title="Lihat">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                <a href="{{ route('user-access.edit', $user) }}" class="p-2 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50" title="Edit">
                                    <span class="material-icons text-sm">edit</span>
                                </a>
                                @if($user->id !== auth()->id())
                                <button type="button" onclick="showDeleteModal('{{ $user->id }}', '{{ $user->name }}')" class="p-2 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50" title="Padam">
                                    <span class="material-icons text-sm">delete</span>
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500">Telefon:</span>
                                <p class="text-gray-900 font-medium">{{ $user->phone }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Tarikh Cipta:</span>
                                <p class="text-gray-900 font-medium">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-500">Kumpulan Akses:</span>
                                <div class="mt-1">
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-600 mr-1 mb-1">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-600">Tiada kumpulan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">
                        <span class="material-icons text-gray-400 text-4xl mb-2">person</span>
                        <p class="text-gray-500 text-sm">Tiada data pengguna akses dijumpai.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-700 text-center sm:text-left">
                        Menunjukkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }} daripada {{ $users->total() }} pengguna
                    </div>
                    <div class="flex justify-center sm:justify-end">
                        {{ $users->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

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
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .action-icon:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
    </style>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3) !important;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <span class="material-icons text-red-600 text-xl">warning</span>
                </div>
                <div class="mt-4 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Padam Pengguna</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Adakah anda pasti mahu memadamkan pengguna <strong id="deleteRecordName"></strong>?
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
                            Padam Pengguna
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
            deleteForm.action = `/user-access/${recordId}`;
            
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
