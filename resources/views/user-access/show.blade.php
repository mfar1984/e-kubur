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
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Lihat Pengguna Akses</h1>
                    <p class="text-xs text-gray-600">Maklumat terperinci pengguna dan akses yang diberikan</p>
                </div>

                <!-- User Information -->
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3">Maklumat Pengguna</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Penuh</label>
                                <p class="text-xs text-gray-900 font-normal">{{ $userAccess->name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                                <p class="text-xs text-gray-900 font-normal">{{ $userAccess->email }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Telefon</label>
                                <p class="text-xs text-gray-900 font-normal">{{ $userAccess->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Dicipta Pada</label>
                                <p class="text-xs text-gray-900 font-normal">{{ $userAccess->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles Section -->
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-3">Kumpulan Akses ({{ $roles->count() }} kumpulan)</h2>
                    
                    @if($roles->count() > 0)
                        <div class="overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-blue-100 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-2 font-medium text-xs">Kumpulan</th>
                                        <th class="px-4 py-2 font-medium text-xs">Guard</th>
                                        <th class="px-4 py-2 font-medium text-xs">Dicipta Pada</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($roles as $role)
                                    <tr class="hover:bg-white">
                                        <td class="px-4 py-2">
                                            <div class="font-normal text-gray-900 text-xs">{{ $role->name }}</div>
                                        </td>
                                        <td class="px-4 py-2 text-gray-900 text-xs font-normal">{{ $role->guard_name }}</td>
                                        <td class="px-4 py-2 text-gray-900 text-xs font-normal">{{ $role->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500">
                                <span class="material-icons text-4xl mb-2 block">group</span>
                                <p class="text-xs font-normal">Tiada kumpulan akses diberikan kepada pengguna ini</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Permissions Section -->
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-3">Izin Akses ({{ $permissions->count() }} izin)</h2>
                    
                    @if($permissions->count() > 0)
                        <div class="overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-blue-100 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-2 font-medium text-xs">Izin</th>
                                        <th class="px-4 py-2 font-medium text-xs">Guard</th>
                                        <th class="px-4 py-2 font-medium text-xs">Dicipta Pada</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($permissions as $permission)
                                    <tr class="hover:bg-white">
                                        <td class="px-4 py-2">
                                            <div class="font-normal text-gray-900 text-xs">{{ $permission->name }}</div>
                                        </td>
                                        <td class="px-4 py-2 text-gray-900 text-xs font-normal">{{ $permission->guard_name }}</td>
                                        <td class="px-4 py-2 text-gray-900 text-xs font-normal">{{ $permission->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500">
                                <span class="material-icons text-4xl mb-2 block">security</span>
                                <p class="text-xs font-normal">Tiada izin akses diberikan kepada pengguna ini</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('user-access.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs rounded-xs hover:bg-gray-200 h-8 flex items-center">
                        <span class="material-icons text-[10px] mr-2">arrow_back</span>
                        Kembali
                    </a>
                    <a href="{{ route('user-access.edit', $userAccess) }}" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 h-8 flex items-center">
                        <span class="material-icons text-[10px] mr-2">edit</span>
                        Edit
                    </a>
                    <button type="button" onclick="showDeleteModal('{{ $userAccess->id }}', '{{ $userAccess->name }}')" class="px-4 py-2 bg-red-600 text-white text-xs rounded-xs hover:bg-red-700 h-8 flex items-center">
                        <span class="material-icons text-[10px] mr-2">delete</span>
                        Padam
                    </button>
                </div>
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
