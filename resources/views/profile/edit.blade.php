<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Edit Profil - E-Kubur' }}</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate" x-data="profileEdit()">
    <x-double-navbar :user="$user" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6 text-center sm:text-left">
                    <h1 class="text-xl font-bold text-gray-900 mb-2">Edit Profil</h1>
                    <p class="text-xs text-gray-600">Kemaskini maklumat profil pengguna</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Profile Information -->
                    <div class="bg-white border border-gray-200 rounded-xs overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                <span class="material-icons text-sm mr-2 text-blue-600">person</span>
                                Maklumat Peribadi
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-xs font-semibold text-gray-700 mb-2">Nama Penuh *</label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                           placeholder="Masukkan nama penuh">
                                    @error('name')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-2">Alamat Emel *</label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                           placeholder="Masukkan alamat emel">
                                    @error('email')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-xs font-semibold text-gray-700 mb-2">Nombor Telefon *</label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                                           placeholder="Contoh: 0123456789">
                                    @error('phone')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Change -->
                    <div class="bg-white border border-gray-200 rounded-xs overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                <span class="material-icons text-sm mr-2 text-green-600">lock</span>
                                Tukar Kata Laluan
                            </h3>
                            <p class="text-xs text-gray-600 mt-1">Biarkan kosong jika tidak mahu menukar kata laluan</p>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Current Password -->
                                <div>
                                    <label for="current_password" class="block text-xs font-semibold text-gray-700 mb-2">Kata Laluan Semasa</label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="current_password" 
                                               name="current_password"
                                               class="w-full px-3 py-2 pr-10 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror"
                                               placeholder="Masukkan kata laluan semasa">
                                        <button type="button" 
                                                @click="togglePassword('current_password')"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <span class="material-icons text-sm" x-text="showCurrentPassword ? 'visibility_off' : 'visibility'"></span>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div>
                                    <label for="new_password" class="block text-xs font-semibold text-gray-700 mb-2">Kata Laluan Baru</label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="new_password" 
                                               name="new_password"
                                               class="w-full px-3 py-2 pr-10 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('new_password') border-red-500 @enderror"
                                               placeholder="Minimum 8 aksara">
                                        <button type="button" 
                                                @click="togglePassword('new_password')"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <span class="material-icons text-sm" x-text="showNewPassword ? 'visibility_off' : 'visibility'"></span>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="md:col-span-2">
                                    <label for="new_password_confirmation" class="block text-xs font-semibold text-gray-700 mb-2">Sahkan Kata Laluan Baru</label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="new_password_confirmation" 
                                               name="new_password_confirmation"
                                               class="w-full px-3 py-2 pr-10 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Masukkan semula kata laluan baru">
                                        <button type="button" 
                                                @click="togglePassword('new_password_confirmation')"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <span class="material-icons text-sm" x-text="showConfirmPassword ? 'visibility_off' : 'visibility'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Requirements -->
                            <div class="mt-4 p-3 bg-blue-50 rounded-xs border border-blue-200">
                                <h4 class="text-xs font-semibold text-blue-900 mb-2">Keperluan Kata Laluan:</h4>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li class="flex items-center">
                                        <span class="material-icons text-xs mr-1">check_circle</span>
                                        Minimum 8 aksara
                                    </li>
                                    <li class="flex items-center">
                                        <span class="material-icons text-xs mr-1">check_circle</span>
                                        Gabungan huruf besar dan kecil
                                    </li>
                                    <li class="flex items-center">
                                        <span class="material-icons text-xs mr-1">check_circle</span>
                                        Nombor dan simbol (disyorkan)
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('profile.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs rounded-xs hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <span class="material-icons text-xs mr-2">cancel</span>
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 transition-colors flex items-center justify-center">
                            <span class="material-icons text-xs mr-2">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function profileEdit() {
            return {
                showCurrentPassword: false,
                showNewPassword: false,
                showConfirmPassword: false,
                
                togglePassword(fieldId) {
                    const field = document.getElementById(fieldId);
                    if (field.type === 'password') {
                        field.type = 'text';
                        if (fieldId === 'current_password') this.showCurrentPassword = true;
                        if (fieldId === 'new_password') this.showNewPassword = true;
                        if (fieldId === 'new_password_confirmation') this.showConfirmPassword = true;
                    } else {
                        field.type = 'password';
                        if (fieldId === 'current_password') this.showCurrentPassword = false;
                        if (fieldId === 'new_password') this.showNewPassword = false;
                        if (fieldId === 'new_password_confirmation') this.showConfirmPassword = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
