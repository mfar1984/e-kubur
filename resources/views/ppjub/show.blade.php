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
        /* Align with kematian/show button style (rounded-xs, h-8) */
        .btn-rounded-xs { border-radius: 4px !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">
    <x-double-navbar :user="$user" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Lihat Ahli PPJUB</h1>
                        <p class="text-xs text-gray-600">Maklumat terperinci ahli PPJUB</p>
                    </div>
                </div>

                <!-- Member Information -->
                <div class="bg-blue-50 p-6 rounded-lg mb-6">
                    <h2 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                        <span class="material-icons text-blue-600 mr-2 text-lg">person</span>
                        Maklumat Ahli
                    </h2>
                    
                    <div class="grid grid-cols-1 gap-4 md:gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Penuh</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $ppjub->nama }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nombor IC</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $ppjub->no_ic }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nombor Telefon</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $ppjub->telefon }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $ppjub->email }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-lg {{ $ppjub->status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $ppjub->status }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tarikh Keahlian</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $ppjub->tarikh_keahlian_formatted }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-green-50 p-6 rounded-lg mb-6">
                    <h2 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                        <span class="material-icons text-green-600 mr-2 text-lg">location_on</span>
                        Maklumat Alamat
                    </h2>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                        <p class="text-xs text-gray-900 font-normal">{{ $ppjub->alamat }}</p>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="material-icons text-gray-600 mr-2 text-lg">info</span>
                        Maklumat Tambahan
                    </h2>
                    
                    <div class="grid grid-cols-1 gap-4 md:gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Umur</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $ppjub->umur }} tahun</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tarikh Dicipta</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $ppjub->created_at_formatted }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tarikh Kemaskini</label>
                            <p class="text-xs text-gray-900 font-normal">{{ $ppjub->updated_at_formatted }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('ppjub.edit', $ppjub) }}" class="h-8 px-4 flex items-center bg-blue-600 text-white text-xs btn-rounded-xs hover:bg-blue-700">
                        <span class="material-icons text-[10px] mr-2">edit</span>
                        Edit
                    </a>
                    <button type="button" onclick="showDeleteModal('{{ $ppjub->id }}', '{{ $ppjub->nama }}')" class="h-8 px-4 flex items-center bg-red-600 text-white text-xs btn-rounded-xs hover:bg-red-700">
                        <span class="material-icons text-[10px] mr-2">delete</span>
                        Padam Ahli
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Padam Ahli PPJUB</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Adakah anda pasti mahu memadamkan ahli PPJUB untuk <strong id="deleteRecordName"></strong>?
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
                            Padam Ahli
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
            deleteForm.action = `/ppjub/${recordId}`;
            
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
