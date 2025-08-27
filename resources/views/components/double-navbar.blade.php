<nav class="bg-white border-b border-gray-200" x-data="navbarData()" x-init="closeAllDropdowns()">

    <!-- Top Navbar -->
    <div class="flex items-center justify-between px-4 md:px-20 h-13 relative">
        <!-- Left Side - Hamburger Menu (Mobile) & Logo (Desktop) -->
        <div class="flex items-center">
            <!-- Hamburger Menu Button - Mobile Only -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none rounded-lg hover:bg-gray-100 transition-colors">
                    <span class="material-icons text-xl" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
                </button>
            </div>
            
            <!-- Logo - Desktop Only -->
            <div class="hidden md:flex items-center space-x-3">
                <img src="{{ asset('images/logo.svg') }}" class="h-12 w-12 py-0" alt="Logo">
            </div>
        </div>
        
        <!-- Centered Logo for Mobile -->
        <div class="absolute left-1/2 transform -translate-x-1/2 md:hidden">
            <img src="{{ asset('images/logo.svg') }}" class="h-12 w-12 py-0" alt="Logo">
        </div>
        
        <!-- Right Side - Mobile Support/Profile Dropdown -->
        <div class="md:hidden flex items-center">
            <div class="relative" x-data="{ open:false }">
                <button @click="open = !open" class="inline-flex items-center justify-center h-10 w-10 rounded-lg text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:outline-none" aria-label="Toggle support menu">
                    <span class="material-icons text-[20px]" x-text="open ? 'close' : 'more_vert'">more_vert</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                    <div class="px-5 py-3 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-900">Bantuan & Sokongan</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Dapatkan bantuan dan rujukan sistem</p>
                    </div>
                    <div class="py-2">
                        <a href="{{ route('user-guide.index') }}" class="flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-blue-500">article</span>
                            Panduan Pengguna
                        </a>
                        <a href="{{ route('faq.index') }}" class="flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-green-500">help</span>
                            Soalan Lazim (FAQ)
                        </a>
                        <a href="{{ route('system-status.index') }}" class="flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-red-500">monitor_heart</span>
                            Status Sistem
                        </a>
                        <a href="{{ route('release-notes.index') }}" class="flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-indigo-500">new_releases</span>
                            Nota Keluaran
                        </a>
                    </div>
                    <div class="border-t border-gray-100"></div>
                    <div class="py-2">
                        <a href="{{ route('profile.index') }}" class="flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3">account_circle</span>
                            Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                                <span class="material-icons text-[16px] mr-3">logout</span>
                                Log Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Center - Waktu Solat (Desktop only) -->
        <div class="hidden md:flex flex-1 items-center justify-center" x-data="prayerTimesWidget()" x-init="fetchPrayerTimes()">
            <div class="flex items-center gap-6 whitespace-nowrap" x-show="loaded" x-cloak>
                <div class="flex flex-col items-center leading-none">
                    <span class="text-[10px] font-bold uppercase tracking-wide text-yellow-600">Imsak</span>
                    <span class="text-xs text-gray-900" :class="timeClass('imsak')" x-text="times.imsak">--:--</span>
                </div>
                <div class="flex flex-col items-center leading-none">
                    <span class="text-[10px] font-bold uppercase tracking-wide text-yellow-600">Subuh</span>
                    <span class="text-xs text-gray-900" :class="timeClass('fajr')" x-text="times.fajr">--:--</span>
                </div>
                <div class="flex flex-col items-center leading-none">
                    <span class="text-[10px] font-bold uppercase tracking-wide text-yellow-600">Syuruk</span>
                    <span class="text-xs text-gray-900" :class="timeClass('syuruk')" x-text="times.syuruk">--:--</span>
                </div>
                <div class="flex flex-col items-center leading-none">
                    <span class="text-[10px] font-bold uppercase tracking-wide text-yellow-600">Dhuha</span>
                    <span class="text-xs text-gray-900" :class="timeClass('dhuha')" x-text="times.dhuha">--:--</span>
                </div>
                <div class="flex flex-col items-center leading-none">
                    <span class="text-[10px] font-bold uppercase tracking-wide text-yellow-600">Zohor</span>
                    <span class="text-xs text-gray-900" :class="timeClass('zuhr')" x-text="times.zuhr">--:--</span>
                </div>
                <div class="flex flex-col items-center leading-none">
                    <span class="text-[10px] font-bold uppercase tracking-wide text-yellow-600">Asar</span>
                    <span class="text-xs text-gray-900" :class="timeClass('asr')" x-text="times.asr">--:--</span>
                </div>
                <div class="flex flex-col items-center leading-none">
                    <span class="text-[10px] font-bold uppercase tracking-wide text-yellow-600">Maghrib</span>
                    <span class="text-xs text-gray-900" :class="timeClass('maghrib')" x-text="times.maghrib">--:--</span>
                </div>
                <div class="flex flex-col items-center leading-none">
                    <span class="text-[10px] font-bold uppercase tracking-wide text-yellow-600">Isyak</span>
                    <span class="text-xs text-gray-900" :class="timeClass('isha')" x-text="times.isha">--:--</span>
                </div>
            </div>
        </div>
        
        
        <!-- Right Side - Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-4">
            <!-- Apps Grid -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="inline-flex items-center justify-center h-9 w-9 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none leading-none align-middle">
                    <span class="material-icons text-[18px] leading-none">apps</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full right-0 mt-2 w-64 bg-white rounded-md shadow-lg py-3 z-50">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-xs font-medium text-gray-900">Aplikasi Pantas</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-3 gap-3">
                            <a href="{{ route('overview') }}" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-blue-600 mb-1">dashboard</span>
                                <span class="text-[10px] text-gray-700">Dashboard</span>
                            </a>
                            <a href="{{ route('kematian.index') }}" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-red-500 mb-1">person</span>
                                <span class="text-[10px] text-gray-700">Kematian</span>
                            </a>
                            <a href="{{ route('ppjub.index') }}" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-blue-500 mb-1">people</span>
                                <span class="text-[10px] text-gray-700">PPJUB</span>
                            </a>
                            <a href="{{ route('tetapan.index') }}" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-blue-500 mb-1">settings</span>
                                <span class="text-[10px] text-gray-700">Tetapan</span>
                            </a>
                            <a href="{{ route('roles.index') }}" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-green-500 mb-1">security</span>
                                <span class="text-[10px] text-gray-700">Roles</span>
                            </a>
                            <a href="{{ route('user-access.index') }}" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-orange-500 mb-1">manage_accounts</span>
                                <span class="text-[10px] text-gray-700">Users</span>
                            </a>
                            <a href="{{ route('integrations.index') }}" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-purple-500 mb-1">integration_instructions</span>
                                <span class="text-[10px] text-gray-700">Integrasi</span>
                            </a>
                            <a href="{{ route('audit-logs.index') }}" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-red-500 mb-1">security</span>
                                <span class="text-[10px] text-gray-700">Audit</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Help -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="inline-flex items-center justify-center h-9 w-9 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none leading-none align-middle">
                    <span class="material-icons text-[18px] leading-none">help_outline</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-3 z-50">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-xs font-medium text-gray-900">Bantuan & Sokongan</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="space-y-2">
                            <h4 class="text-xs font-medium text-gray-800">Bantuan Pantas</h4>
                            <div class="space-y-1">
                                <a href="{{ route('user-guide.index') }}" class="flex items-center text-xs text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-xs mr-2 text-blue-500">article</span>
                                    Panduan Pengguna
                                </a>
                                <a href="{{ route('faq.index') }}" class="flex items-center text-xs text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-xs mr-2 text-green-500">help</span>
                                    Soalan Lazim (FAQ)
                                </a>
                                <a href="#" class="flex items-center text-xs text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-xs mr-2 text-orange-500">support_agent</span>
                                    Hubungi Sokongan
                                </a>
                                <a href="{{ route('system-status.index') }}" class="flex items-center text-xs text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-xs mr-2 text-red-500">monitor_heart</span>
                                    Status Sistem
                                </a>
                                <a href="{{ route('release-notes.index') }}" class="flex items-center text-xs text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-xs mr-2 text-indigo-500">new_releases</span>
                                    Nota Keluaran
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-xs font-medium text-gray-700 hover:text-blue-500 focus:outline-none">
                    <span class="material-icons text-sm mr-2">account_circle</span>
                    {{ Auth::user()->name }}
                    <span class="material-icons text-xs font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full right-0 mt-1 w-48 bg-white rounded-md shadow-lg py-2 z-50">
                    <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">Profil</a>
                                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">Edit Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">Log Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Navbar - Hidden on Mobile -->
    <div class="hidden md:block bg-white border-t border-gray-100">
        <div class="flex space-x-6 px-20 h-12 items-center justify-between">
            <div class="flex space-x-6">
                <a href="{{ route('overview') }}" class="relative flex items-center text-xs font-normal text-gray-700 hover:text-blue-400">
                    <span class="material-icons text-sm mr-1 text-blue-600">dashboard</span>
                    Paparan Pemuka
                </a>

                <!-- Pengurusan -->
                <div class="relative" x-data="{ open: false, timeout: null }">
                    <button @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => open = false, 200)" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-sm mr-1 text-green-600">fact_check</span>
                        Pengurusan
                        <span class="material-icons text-xs font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => open = false, 200)" class="absolute top-full left-0 mt-1 w-56 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="{{ route('kematian.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Daftar Kematian
                            <div class="absolute top-0 right-0 w-1 h-full bg-red-500"></div>
                        </a>
                        <a href="{{ route('ppjub.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Ahli PPJUB
                            <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                        </a>
                    </div>
                </div>
                
                <!-- Pentadbiran Sistem -->
                <div class="relative" x-data="{ open: false, timeout: null }">
                    <button @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => open = false, 200)" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-sm mr-1 text-red-600">admin_panel_settings</span>
                        Pentadbiran Sistem
                        <span class="material-icons text-xs font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => open = false, 200)" class="absolute top-full left-0 mt-1 w-64 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="{{ route('tetapan.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Tetapan Umum
                            <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                        </a>
                        <a href="{{ route('roles.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Kumpulan Akses
                            <div class="absolute top-0 right-0 w-1 h-full bg-green-500"></div>
                        </a>
                        <a href="{{ route('user-access.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Pengguna Akses
                            <div class="absolute top-0 right-0 w-1 h-full bg-orange-500"></div>
                        </a>
                        <a href="{{ route('integrations.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Integrasi
                            <div class="absolute top-0 right-0 w-1 h-full bg-purple-500"></div>
                        </a>
                        <a href="{{ route('audit-logs.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Log Audit & Keselamatan
                            <div class="absolute top-0 right-0 w-1 h-full bg-red-500"></div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Weather Widget - Hidden on Mobile -->
            <div x-data="weatherWidget()" x-init="fetchWeather()" class="relative hidden md:block">
                <div @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" class="flex items-center space-x-2 text-xs text-gray-600 cursor-pointer">
                    <span class="material-icons text-sm" :class="weatherIconColor" x-text="weatherIcon">wb_sunny</span>
                    <span x-text="temperature + '°C'">--°C</span>
                    <span class="text-gray-400">|</span>
                    <span x-text="condition">Loading...</span>
                </div>
                
                <!-- Weather Tooltip -->
                <div x-show="showTooltip" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute top-full right-0 mt-3 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                    
                    <!-- Weather Header -->
                    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900" x-text="'Cuaca ' + (weatherData?.location?.city || 'Sibu') + ', Malaysia'">Cuaca Sibu, Malaysia</h3>
                                <p class="text-xs text-gray-600" x-text="new Date().toLocaleDateString('ms-MY', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })">Loading...</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900" x-text="temperature + '°C'">--°C</div>
                                <p class="text-xs text-gray-600" x-text="condition">Loading...</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Weather Details -->
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <span class="material-icons text-2xl text-blue-500 mb-2" :class="weatherIconColor" x-text="weatherIcon">wb_sunny</span>
                                <p class="text-xs text-gray-600">Keadaan</p>
                                <p class="text-sm font-medium text-gray-900" x-text="condition">Loading...</p>
                            </div>
                            <div class="text-center">
                                <span class="material-icons text-2xl text-gray-400 mb-2">thermostat</span>
                                <p class="text-xs text-gray-600">Suhu</p>
                                <p class="text-sm font-medium text-gray-900" x-text="temperature + '°C'">--°C</p>
                            </div>
                        </div>
                        
                        <!-- Current Weather Details Grid -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-medium text-gray-800 mb-3 text-center">Maklumat Semasa</h4>
                        <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Terasa Seperti:</span>
                                    <span class="font-medium text-gray-900" x-text="current.feelsLike + '°C'">--°C</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Kelembapan:</span>
                                    <span class="font-medium text-gray-900" x-text="current.humidity + '%'">--%</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Kelajuan Angin:</span>
                                    <span class="font-medium text-gray-900" x-text="current.windSpeed + ' km/j'">-- km/j</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Tekanan:</span>
                                    <span class="font-medium text-gray-900" x-text="current.pressure + ' hPa'">-- hPa</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Jarak Penglihatan:</span>
                                    <span class="font-medium text-gray-900" x-text="current.visibility + ' km'">-- km</span>
                            </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Indeks UV:</span>
                                    <span class="font-medium text-gray-900" x-text="current.uvIndex">--</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Forecast Section -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-medium text-gray-800 mb-3 text-center">Ramalan Hari Ini</h4>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="text-center">
                                    <div class="flex items-center justify-center space-x-1 mb-1">
                                        <span class="material-icons text-sm text-red-400">thermostat</span>
                                        <span class="text-xs text-gray-600">Min</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900" x-text="forecast.minTemp + '°C'">--°C</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center space-x-1 mb-1">
                                        <span class="material-icons text-sm text-blue-400">thermostat</span>
                                        <span class="text-xs text-gray-600">Max</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900" x-text="forecast.maxTemp + '°C'">--°C</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center space-x-1 mb-1">
                                        <span class="material-icons text-sm text-blue-500">opacity</span>
                                        <span class="text-xs text-gray-600">Hujan</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900" x-text="forecast.precipitation + '%'">--%</p>
                                </div>
                            </div>
                            
                            <!-- Additional Forecast Info -->
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="flex items-center justify-between text-xs text-gray-600">
                                    <span>Kelembapan:</span>
                                    <span x-text="forecast.humidity + '%'">--%</span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-gray-600 mt-1">
                                    <span>Keadaan:</span>
                                    <span x-text="forecast.condition">Loading...</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Info -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-xs text-gray-600">
                                <span>Dikemas kini:</span>
                                <span x-text="new Date().toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit' })">--:--</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu (dropdown-style, top-right; toggle with hamburger) -->
    <div x-show="mobileMenuOpen" class="md:hidden absolute right-2 top-14 z-50">
        <div class="w-80 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden" @click.away="mobileMenuOpen = false">
            <!-- Header (toggled via hamburger; no close button here) -->
            <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-sm font-semibold text-gray-900">Menu Navigasi</h3>
                <p class="text-[11px] text-gray-500">Akses pantas modul dan pentadbiran</p>
            </div>
            <div class="p-2 space-y-1">
                <!-- Papan Pemuka -->
                <a href="{{ route('overview') }}" @click="mobileMenuOpen=false" class="block px-3 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                    <span class="material-icons text-sm mr-2 text-blue-600">dashboard</span>
                    Papan Pemuka
                </a>
                <!-- Pengurusan Section -->
                <div class="border-t border-gray-100 pt-3 mt-3">
                    <h3 class="px-3 py-1 text-[11px] font-semibold text-gray-600 uppercase tracking-wider">Pengurusan</h3>
                    <a href="{{ route('kematian.index') }}" @click="mobileMenuOpen=false" class="block px-3 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                        <span class="material-icons text-sm mr-2 text-red-500">person</span>
                        Daftar Kematian
                    </a>
                    <a href="{{ route('ppjub.index') }}" @click="mobileMenuOpen=false" class="block px-3 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                        <span class="material-icons text-sm mr-2 text-blue-500">people</span>
                        Ahli PPJUB
                    </a>
                </div>
                <!-- Pentadbiran Sistem Section -->
                <div class="border-t border-gray-100 pt-3 mt-3">
                    <h3 class="px-3 py-1 text-[11px] font-semibold text-gray-600 uppercase tracking-wider">Pentadbiran Sistem</h3>
                    <a href="{{ route('tetapan.index') }}" @click="mobileMenuOpen=false" class="block px-3 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                        <span class="material-icons text-sm mr-2 text-blue-500">settings</span>
                        Tetapan Umum
                    </a>
                    <a href="{{ route('roles.index') }}" @click="mobileMenuOpen=false" class="block px-3 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                        <span class="material-icons text-sm mr-2 text-green-500">security</span>
                        Kumpulan Akses
                    </a>
                    <a href="{{ route('user-access.index') }}" @click="mobileMenuOpen=false" class="block px-3 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                        <span class="material-icons text-sm mr-2 text-orange-500">manage_accounts</span>
                        Pengguna Akses
                    </a>
                    <a href="{{ route('integrations.index') }}" @click="mobileMenuOpen=false" class="block px-3 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                        <span class="material-icons text-sm mr-2 text-purple-500">integration_instructions</span>
                        Integrasi
                    </a>
                    <a href="{{ route('audit-logs.index') }}" @click="mobileMenuOpen=false" class="block px-3 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                        <span class="material-icons text-sm mr-2 text-red-500">security</span>
                        Log Audit & Keselamatan
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function navbarData() {
    return {
        userDropdownOpen: false,
        mobileMenuOpen: false,
        
        // Close all dropdowns when page loads or navigation occurs
        closeAllDropdowns() {
            this.userDropdownOpen = false;
            this.mobileMenuOpen = false;
            
            // Close any other dropdowns
            document.querySelectorAll('[x-data*="open"]').forEach(el => {
                if (el._x_dataStack && el._x_dataStack[0] && typeof el._x_dataStack[0].open !== 'undefined') {
                    el._x_dataStack[0].open = false;
                }
            });
        }
    }
}

function prayerTimesWidget(){
    return {
        loaded: false,
        times: { imsak: '--:--', fajr: '--:--', syuruk: '--:--', dhuha: '--:--', zuhr: '--:--', asr: '--:--', maghrib: '--:--', isha: '--:--' },
        async fetchPrayerTimes(){
            try {
                // Get selected zone from meta or fallback (server can render into a meta tag later if needed)
                const res = await fetch('/api/esolat/today');
                const data = await res.json();
                if (data && data.success && data.times) {
                    this.times = data.times;
                    this.loaded = true;
                } else {
                    this.loaded = true; // show with defaults
                }
            } catch(e){ this.loaded = true; }
        },
        // Return Date for today at given time string like "05:20 AM"
        toToday(timeStr){
            if (!timeStr || timeStr.includes('--')) return null;
            const m = timeStr.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i);
            if (!m) return null;
            let h = parseInt(m[1],10); const min = parseInt(m[2],10); const ap = m[3].toUpperCase();
            if (ap === 'PM' && h !== 12) h += 12; if (ap === 'AM' && h === 12) h = 0;
            const d = new Date(); d.setSeconds(0,0); d.setHours(h, min, 0, 0); return d;
        },
        // Is time within next 15 minutes
        isImminent(key){
            const t = this.toToday(this.times[key]); if (!t) return false;
            const now = new Date(); const diffMs = t - now; const diffMin = diffMs / 60000;
            return diffMin >= 0 && diffMin <= 15;
        },
        timeClass(key){
            return this.isImminent(key) ? 'font-extrabold animate-blink text-yellow-700' : 'font-normal';
        }
    }
}

function weatherWidget() {
    return {
        showTooltip: false,
        temperature: '--',
        condition: 'Loading...',
        weatherIcon: 'wb_sunny',
        weatherIconColor: 'text-blue-500',
        weatherData: null,
        current: {
            humidity: '--',
            windSpeed: '--',
            feelsLike: '--',
            pressure: '--',
            visibility: '--',
            uvIndex: '--'
        },
        forecast: {
            minTemp: '--',
            maxTemp: '--',
            condition: 'Loading...',
            precipitation: '--',
            humidity: '--'
        },
        
        async fetchWeather() {
            try {
                const response = await fetch('/weather');
                const data = await response.json();
                
                if (data.success) {
                    this.weatherData = data.data;
                    this.temperature = data.data.current.temperature;
                    this.condition = data.data.current.condition;
                    this.weatherIcon = this.getWeatherIcon(data.data.current.weatherCode);
                    this.weatherIconColor = this.getWeatherIconColor(data.data.current.weatherCode);
                    
                    // Add current weather details
                    if (data.data.current) {
                        this.current.humidity = data.data.current.humidity || '--';
                        this.current.windSpeed = data.data.current.windSpeed || '--';
                        this.current.feelsLike = data.data.current.feelsLike || '--';
                        this.current.pressure = data.data.current.pressure || '--';
                        this.current.visibility = data.data.current.visibility || '--';
                        this.current.uvIndex = data.data.current.uvIndex || '--';
                    }
                    
                    // Add forecast data
                    if (data.data.forecast) {
                        this.forecast.minTemp = data.data.forecast.temperature.min;
                        this.forecast.maxTemp = data.data.forecast.temperature.max;
                        this.forecast.condition = data.data.forecast.condition;
                        this.forecast.precipitation = data.data.forecast.precipitation;
                        this.forecast.humidity = data.data.forecast.humidity;
                    }
                } else {
                    // Set default values if API fails
                    this.temperature = '24';
                    this.condition = 'Cerah';
                    this.weatherIcon = 'wb_sunny';
                    this.weatherIconColor = 'text-yellow-500';
                    this.current = {
                        humidity: '70',
                        windSpeed: '5',
                        feelsLike: '26',
                        pressure: '1013',
                        visibility: '10',
                        uvIndex: '5'
                    };
                    this.forecast = {
                        minTemp: '22',
                        maxTemp: '28',
                        condition: 'Cerah',
                        precipitation: '10',
                        humidity: '75'
                    };
                    // Set default location
                    this.weatherData = {
                        location: {
                            city: 'Sibu'
                        }
                    };
                }
            } catch (error) {
                console.error('Weather fetch error:', error);
                // Set fallback values
                this.temperature = '24';
                this.condition = 'Cerah';
                this.weatherIcon = 'wb_sunny';
                this.weatherIconColor = 'text-yellow-500';
                this.current = {
                    humidity: '70',
                    windSpeed: '5',
                    feelsLike: '26',
                    pressure: '1013',
                    visibility: '10',
                    uvIndex: '5'
                };
                this.forecast = {
                    minTemp: '22',
                    maxTemp: '28',
                    condition: 'Cerah',
                    precipitation: '10',
                    humidity: '75'
                };
                // Set default location
                this.weatherData = {
                    location: {
                        city: 'Sibu'
                    }
                };
            }
        },
        
        getWeatherIcon(code) {
            const icons = {
                1000: 'wb_sunny',      // Clear
                1001: 'cloud',         // Cloudy
                1100: 'wb_sunny',      // Mostly Clear
                1101: 'cloud',         // Partly Cloudy
                1102: 'cloud',         // Mostly Cloudy
                2000: 'cloud',         // Fog
                4000: 'grain',         // Light Rain
                4001: 'rainy',         // Rain
                4200: 'grain',         // Light Rain
                4201: 'rainy',         // Heavy Rain
                5000: 'ac_unit',       // Snow
                5001: 'ac_unit',       // Flurries
                5100: 'ac_unit',       // Light Snow
                5101: 'ac_unit',       // Heavy Snow
                6000: 'grain',         // Freezing Drizzle
                6200: 'grain',         // Light Freezing Rain
                6201: 'rainy',         // Freezing Rain
                7000: 'ac_unit',       // Ice Pellets
                7101: 'ac_unit',       // Heavy Ice Pellets
                7102: 'ac_unit',       // Light Ice Pellets
                8000: 'thunderstorm'   // Thunderstorm
            };
            return icons[code] || 'wb_sunny';
        },
        
        getWeatherIconColor(code) {
            if (code >= 4000 && code <= 4201) return 'text-blue-500'; // Rain
            if (code >= 5000 && code <= 5101) return 'text-gray-400'; // Snow
            if (code >= 6000 && code <= 6201) return 'text-blue-400'; // Freezing
            if (code >= 7000 && code <= 7102) return 'text-gray-400'; // Ice
            if (code === 8000) return 'text-yellow-500'; // Thunderstorm
            if (code >= 1101 && code <= 1102) return 'text-gray-400'; // Cloudy
            if (code === 2000) return 'text-gray-400'; // Fog
            return 'text-yellow-500'; // Clear/Sunny
        }
    }
}
</script> 

<style>
@keyframes blink15 { 0%,49% { opacity:1 } 50%,100% { opacity:.25 } }
.animate-blink { animation: blink15 1s step-start infinite; }
</style>
