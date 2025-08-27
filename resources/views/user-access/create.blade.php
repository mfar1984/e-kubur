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
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Pengguna Akses</h1>
                    <p class="text-xs text-gray-600">Cipta pengguna baru dengan kumpulan akses yang sesuai</p>
                </div>

                <!-- Form -->
                <form action="{{ route('user-access.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- User Information -->
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-4">
                        <h2 class="text-sm font-medium text-gray-900 mb-4">Maklumat Pengguna</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nama -->
                            <div>
                                <label for="name" class="block text-xs font-medium text-gray-700 mb-2">Nama Penuh</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Contoh: Ahmad bin Ali"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-xs font-medium text-gray-700 mb-2">Alamat Email</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Contoh: ahmad@email.com"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-xs font-medium text-gray-700 mb-2">Nombor Telefon</label>
                                <input type="text" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Contoh: 012-3456789"
                                       required>
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-xs font-medium text-gray-700 mb-2">Kata Laluan</label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Minimum 8 aksara"
                                       required>
                                @error('password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-2">Sahkan Kata Laluan</label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Ulang kata laluan"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Role Assignment -->
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-4">
                        <h2 class="text-sm font-xs text-gray-600 mb-4">Kumpulan Akses</h2>
                        
                        @if($roles->count() > 0)
                            <div class="space-y-3">
                                @foreach($roles as $role)
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="role_{{ $role->id }}" 
                                           name="roles[]" 
                                           value="{{ $role->name }}"
                                           class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500"
                                           {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                    <label for="role_{{ $role->id }}" class="ml-3 text-xs text-gray-600">
                                        <span class="font-medium">{{ $role->name }}</span>
                                        <span class="text-gray-500 ml-2">({{ $role->permissions->count() }} izin)</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="text-gray-500">
                                    <span class="material-icons text-2xl mb-2 block">security</span>
                                    <p class="text-sm">Tiada kumpulan akses tersedia</p>
                                </div>
                            </div>
                        @endif
                        
                        @error('roles')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('user-access.index') }}" class="px-4 py-2 bg-red-100 text-red-700 text-xs rounded-xs hover:bg-red-200 h-8 flex items-center">
                            <span class="material-icons text-[10px] mr-2">close</span>
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 h-8 flex items-center">
                            <span class="material-icons text-[10px] mr-2">save</span>
                            Simpan Pengguna
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
