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
    </style>
    
    <script>
        function formatIC(input) {
            // Remove all non-digits
            let value = input.value.replace(/\D/g, '');
            
            // Limit to 12 digits
            if (value.length > 12) {
                value = value.substring(0, 12);
            }
            
            // Format with dashes: DDMMYY-DD-DDDD
            if (value.length >= 6) {
                value = value.substring(0, 6) + '-' + value.substring(6);
            }
            if (value.length >= 9) {
                value = value.substring(0, 9) + '-' + value.substring(9);
            }
            
            input.value = value;
        }
    </script>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">
    <x-double-navbar :user="$user" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Ahli PPJUB</h1>
                        <p class="text-xs text-gray-600">Tambah ahli PPJUB baru ke dalam sistem</p>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('ppjub.store') }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-4 md:gap-6 md:grid-cols-2">
                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-xs font-medium text-gray-700 mb-2">Nama Penuh *</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900 @error('nama') border-b-red-500 @enderror"
                                placeholder="Contoh: Ahmad bin Ali">
                            @error('nama')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. IC -->
                        <div>
                            <label for="no_ic" class="block text-xs font-medium text-gray-700 mb-2">Nombor IC *</label>
                            <input type="text" id="no_ic" name="no_ic" value="{{ old('no_ic') }}" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900 @error('no_ic') border-b-red-500 @enderror"
                                placeholder="Contoh: 891230-13-1581" maxlength="14" oninput="formatIC(this)">
                            @error('no_ic')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefon -->
                        <div>
                            <label for="telefon" class="block text-xs font-medium text-gray-700 mb-2">Nombor Telefon *</label>
                            <input type="text" id="telefon" name="telefon" value="{{ old('telefon') }}" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900 @error('telefon') border-b-red-500 @enderror"
                                placeholder="Contoh: 012-3456789">
                            @error('telefon')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-900 @error('email') border-b-red-500 @enderror"
                                placeholder="Contoh: ahmad@email.com">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none text-gray-700 @error('status') border-b-red-500 @enderror">
                                <option value="">Pilih Status</option>
                                <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tarikh Keahlian -->
                        <div>
                            <label for="tarikh_keahlian" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Keahlian *</label>
                            <input type="date" id="tarikh_keahlian" name="tarikh_keahlian" value="{{ old('tarikh_keahlian') }}" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none text-gray-700 @error('tarikh_keahlian') border-b-red-500 @enderror">
                            @error('tarikh_keahlian')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-xs font-medium text-gray-700 mb-2">Alamat *</label>
                        <textarea id="alamat" name="alamat" rows="3" required
                            class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none placeholder-gray-500 text-gray-700 @error('alamat') border-b-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('ppjub.index') }}" class="h-10 px-4 flex items-center justify-center text-xs text-gray-700 bg-red-100 rounded-xs hover:bg-red-200">
                            Batal
                        </a>
                        <button type="submit" class="h-10 px-4 flex items-center justify-center bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700">
                            <span class="material-icons text-[10px] mr-2">save</span>
                            Simpan Ahli
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
