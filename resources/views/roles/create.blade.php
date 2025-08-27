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
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Kumpulan Akses</h1>
                    <p class="text-xs text-gray-600">Cipta kumpulan akses baru dengan izin yang sesuai</p>
                </div>

                <!-- Form -->
                <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Nama Kumpulan -->
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-2">Nama Kumpulan</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Contoh: Admin, Pengguna, Moderator"
                               required>
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permissions Table -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-3">Izin Akses</label>
                        
                        <!-- Table Header -->
                        <div class="overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-blue-100 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-2 font-medium text-xs w-1/3">Kategori</th>
                                        <th class="px-4 py-2 font-medium text-xs text-center">Create</th>
                                        <th class="px-4 py-2 font-medium text-xs text-center">Read</th>
                                        <th class="px-4 py-2 font-medium text-xs text-center">Update</th>
                                        <th class="px-4 py-2 font-medium text-xs text-center">Delete</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <!-- Paparan Pemuka -->
                                    <tr class="hover:bg-white">
                                        <td class="px-4 py-2">
                                            <div class="font-normal text-gray-600 text-xs">Paparan Pemuka</div>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="view overview" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="view overview" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500" checked disabled>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="edit overview" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="delete overview" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                    </tr>

                                    <!-- Pengurusan - Daftar Kematian -->
                                    <tr class="hover:bg-white bg-gray-25">
                                        <td class="px-4 py-2">
                                            <div class="font-normal text-gray-600 text-xs pl-4">Daftar Kematian</div>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="create kematian" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="view kematian" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="edit kematian" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="delete kematian" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                    </tr>

                                    <!-- Pengurusan - Ahli PPJUB -->
                                    <tr class="hover:bg-white bg-gray-25">
                                        <td class="px-4 py-2">
                                            <div class="font-normal text-gray-600 text-xs pl-4">Ahli PPJUB</div>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="create ppjub" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="view ppjub" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="edit ppjub" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="delete ppjub" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                    </tr>

                                    <!-- Pentadbiran Sistem - Tetapan Umum -->
                                    <tr class="hover:bg-white">
                                        <td class="px-4 py-2">
                                            <div class="font-normal text-gray-600 text-xs">Tetapan Umum</div>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="create settings" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="view settings" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="edit settings" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="delete settings" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                    </tr>

                                    <!-- Pentadbiran Sistem - Kumpulan Akses -->
                                    <tr class="hover:bg-white bg-gray-25">
                                        <td class="px-4 py-2">
                                            <div class="font-normal text-gray-600 text-xs pl-4">Kumpulan Akses</div>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="create roles" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="view roles" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="edit roles" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="delete roles" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                    </tr>

                                    <!-- Pentadbiran Sistem - Pengguna Akses -->
                                    <tr class="hover:bg-white bg-gray-25">
                                        <td class="px-4 py-2">
                                            <div class="font-normal text-gray-600 text-xs pl-4">Pengguna Akses</div>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="create users" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="view users" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="edit users" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="delete users" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                    </tr>

                                    <!-- Pentadbiran Sistem - Integrasi -->
                                    <tr class="hover:bg-white bg-gray-25">
                                        <td class="px-4 py-2">
                                            <div class="font-normal text-gray-600 text-xs pl-4">Integrasi</div>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="create integration" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="view integration" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="edit integration" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="delete integration" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                    </tr>

                                    <!-- Pentadbiran Sistem - Log Audit & Keselamatan -->
                                    <tr class="hover:bg-white bg-gray-25">
                                        <td class="px-4 py-2">
                                            <div class="font-normal text-gray-600 text-xs pl-4">Log Audit & Keselamatan</div>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="create audit" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="view audit" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="edit audit" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="permissions[]" value="delete audit" class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        @error('permissions')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-red-100 text-red-700 text-xs rounded-xs hover:bg-red-200 h-8 flex items-center">
                            <span class="material-icons text-[10px] mr-2">close</span>
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 h-8 flex items-center">
                            <span class="material-icons text-[10px] mr-2">save</span>
                            Simpan Kumpulan
                        </button>
                    </div>
                </form>
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
        
        .bg-gray-25 {
            background-color: #fafafa;
        }
        
        /* Custom checkbox styling for white background */
        input[type="checkbox"] {
            background-color: white !important;
            border: 1px solid #d1d5db !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            width: 16px !important;
            height: 16px !important;
            border-radius: 3px !important;
        }
        
        input[type="checkbox"]:checked {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e") !important;
            background-size: 12px !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
        }
        
        input[type="checkbox"]:focus {
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2) !important;
        }
    </style>
</body>
</html>
