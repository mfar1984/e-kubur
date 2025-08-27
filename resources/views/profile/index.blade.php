<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Profil Pengguna - E-Kubur' }}</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">
    <x-double-navbar :user="$user" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6 text-center sm:text-left">
                    <h1 class="text-xl font-bold text-gray-900 mb-2">Profil Pengguna</h1>
                    <p class="text-xs text-gray-600">Maklumat dan pengurusan profil pengguna</p>
                </div>

                @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xs p-4">
                    <div class="flex items-center">
                        <span class="material-icons text-green-600 mr-2">check_circle</span>
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <!-- Profile Information -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Profile Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-xs border border-gray-200 p-6 text-center">
                            <!-- Profile Avatar -->
                            <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-4">
                                <span class="material-icons text-3xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            
                            <!-- User Info -->
                            <h2 class="text-lg font-semibold text-gray-900 mb-1">{{ $user->name }}</h2>
                            <p class="text-xs text-gray-600 mb-4">{{ $user->email }}</p>
                            
                            <!-- Status Badge -->
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                Aktif
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="space-y-2">
                                <a href="{{ route('profile.edit') }}" class="w-full px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 transition-colors flex items-center justify-center">
                                    <span class="material-icons text-xs mr-2">edit</span>
                                    Edit Profil
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Details -->
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-gray-200 rounded-xs overflow-hidden">
                            <!-- Details Header -->
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                    <span class="material-icons text-sm mr-2 text-blue-600">person</span>
                                    Maklumat Profil
                                </h3>
                            </div>
                            
                            <!-- Details Content -->
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Personal Information -->
                                    <div class="space-y-4">
                                        <h4 class="text-xs font-normal text-gray-700 uppercase tracking-wide">Maklumat Peribadi</h4>
                                        
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Penuh</label>
                                                <p class="text-xs text-gray-900">{{ $user->name }}</p>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat Emel</label>
                                                <p class="text-xs text-gray-900">{{ $user->email }}</p>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Nombor Telefon</label>
                                                <p class="text-xs text-gray-900">{{ $user->phone ?? 'Tidak dinyatakan' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Account Information -->
                                    <div class="space-y-4">
                                        <h4 class="text-xs font-normal text-gray-700 uppercase tracking-wide">Maklumat Akaun</h4>
                                        
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">ID Pengguna</label>
                                                <p class="text-xs text-gray-900 font-mono">{{ $user->id }}</p>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Tarikh Daftar</label>
                                                <p class="text-xs text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Kemaskini Terakhir</label>
                                                <p class="text-xs text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Roles & Permissions -->
                        <div class="mt-6 bg-white border border-gray-200 rounded-xs overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                    <span class="material-icons text-sm mr-2 text-green-600">security</span>
                                    Kumpulan Akses & Izin
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                @if($user->roles->count() > 0)
                                <div class="space-y-4">
                                    <h4 class="text-xs font-normal text-gray-700 uppercase tracking-wide">Kumpulan Akses</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <span class="material-icons text-xs mr-1">group</span>
                                            {{ $role->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    
                                    <h4 class="text-xs font-normal text-gray-700 uppercase tracking-wide mt-4">Izin</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @php
                                            $permissions = $user->getAllPermissions();
                                        @endphp
                                        @if($permissions->count() > 0)
                                            @foreach($permissions->take(6) as $permission)
                                            <div class="flex items-center text-xs">
                                                <span class="material-icons text-xs text-green-600 mr-2">check_circle</span>
                                                <span class="text-xs text-gray-700">{{ $permission->name }}</span>
                                            </div>
                                            @endforeach
                                            @if($permissions->count() > 6)
                                            <div class="text-xs text-gray-500">
                                                +{{ $permissions->count() - 6 }} izin lagi
                                            </div>
                                            @endif
                                        @else
                                            <div class="text-xs text-gray-500">Tiada izin khusus</div>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <span class="material-icons text-gray-400 text-2xl mb-2">security</span>
                                    <p class="text-sm text-gray-500">Tiada kumpulan akses yang ditetapkan</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="mt-6 bg-white border border-gray-200 rounded-xs overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                    <span class="material-icons text-sm mr-2 text-purple-600">history</span>
                                    Aktiviti Terkini
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                <div class="space-y-3">
                                    <div class="flex items-center text-xs">
                                        <span class="material-icons text-xs text-blue-600 mr-2">login</span>
                                        <span class="text-xs text-gray-700">Log masuk ke sistem</span>
                                        <span class="text-xs text-gray-500 ml-auto">{{ now()->format('H:i') }}</span>
                                    </div>
                                    <div class="flex items-center text-xs">
                                        <span class="material-icons text-xs text-green-600 mr-2">update</span>
                                        <span class="text-xs text-gray-700">Kemaskini profil</span>
                                        <span class="text-xs text-gray-500 ml-auto">{{ $user->updated_at->format('H:i') }}</span>
                                    </div>
                                    <div class="flex items-center text-xs">
                                        <span class="material-icons text-xs text-purple-600 mr-2">settings</span>
                                        <span class="text-xs text-gray-700">Ubah tetapan sistem</span>
                                        <span class="text-xs text-gray-500 ml-auto">2 jam lepas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
