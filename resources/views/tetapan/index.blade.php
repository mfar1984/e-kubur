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
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Tetapan Umum</h1>
                    <p class="text-xs text-gray-600">Konfigurasi sistem dan tetapan asas</p>
                </div>

                <!-- Settings Form -->
                <form method="POST" action="{{ route('tetapan.bulk-update') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Umum Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="material-icons text-sm mr-2 text-blue-600">settings</span>
                            Tetapan Umum
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nama Sistem -->
                            <div>
                                <label for="nama_sistem" class="block text-xs font-medium text-gray-700 mb-2">Nama Sistem</label>
                                <input type="text" 
                                       id="nama_sistem" 
                                       name="tetapan[nama_sistem]" 
                                       value="{{ $tetapan->where('kunci', 'nama_sistem')->first()?->nilai ?? 'E-Kubur' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Masukkan nama sistem">
                                <p class="mt-1 text-xs text-gray-500">Nama rasmi sistem pengurusan jenazah</p>
                            </div>

                            <!-- Versi Sistem -->
                            <div>
                                <label for="versi_sistem" class="block text-xs font-medium text-gray-700 mb-2">Versi Sistem</label>
                                <input type="text" 
                                       id="versi_sistem" 
                                       name="tetapan[versi_sistem]" 
                                       value="{{ $tetapan->where('kunci', 'versi_sistem')->first()?->nilai ?? '1.0.0' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs text-gray-900 bg-gray-100"
                                       readonly>
                                <p class="mt-1 text-xs text-gray-500">Versi semasa sistem (tidak boleh diubah)</p>
                            </div>

                            <!-- Alamat Sistem -->
                            <div class="md:col-span-2">
                                <label for="alamat_sistem" class="block text-xs font-medium text-gray-700 mb-2">Alamat Sistem</label>
                                <input type="text" 
                                       id="alamat_sistem" 
                                       name="tetapan[alamat_sistem]" 
                                       value="{{ $tetapan->where('kunci', 'alamat_sistem')->first()?->nilai ?? 'Jalan Masjid, 93000 Kuching, Sarawak' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Masukkan alamat sistem">
                                <p class="mt-1 text-xs text-gray-500">Alamat rasmi sistem</p>
                            </div>

                            <!-- Lokasi Default Latitude -->
                            <div>
                                <label for="default_latitude" class="block text-xs font-medium text-gray-700 mb-2">Latitude Default</label>
                                <input type="number" 
                                       id="default_latitude" 
                                       name="tetapan[default_latitude]" 
                                       value="{{ $tetapan->where('kunci', 'default_latitude')->first()?->nilai ?? '2.3000' }}"
                                       step="any"
                                       min="-90" max="90"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Contoh: 2.3000">
                                <p class="mt-1 text-xs text-gray-500">Latitude default untuk maps (Kuching: 2.3000)</p>
                            </div>

                            <!-- Lokasi Default Longitude -->
                            <div>
                                <label for="default_longitude" class="block text-xs font-medium text-gray-700 mb-2">Longitude Default</label>
                                <input type="number" 
                                       id="default_longitude" 
                                       name="tetapan[default_longitude]" 
                                       value="{{ $tetapan->where('kunci', 'default_longitude')->first()?->nilai ?? '111.8167' }}"
                                       step="any"
                                       min="-180" max="180"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Contoh: 111.8167">
                                <p class="mt-1 text-xs text-gray-500">Longitude default untuk maps (Kuching: 111.8167)</p>
                            </div>

                            <!-- Zon Waktu Solat (e-Solat) -->
                            <div>
                                <label for="prayer_zone" class="block text-xs font-medium text-gray-700 mb-2">Zon Waktu Solat (e‑Solat JAKIM)</label>
                                @php $selectedZone = $tetapan->where('kunci', 'prayer_zone')->first()?->nilai ?? 'SWK16'; @endphp
                                <select id="prayer_zone" name="tetapan[prayer_zone]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="" disabled {{ $selectedZone ? '' : 'selected' }}>Tukar Zon..</option>
                                    <optgroup label="Johor">
                                        <option value="JHR01" {{ $selectedZone==='JHR01' ? 'selected' : '' }}>JHR01 - Pulau Aur dan Pulau Pemanggil</option>
                                        <option value="JHR02" {{ $selectedZone==='JHR02' ? 'selected' : '' }}>JHR02 - Johor Bahru, Kota Tinggi, Mersing, Kulai</option>
                                        <option value="JHR03" {{ $selectedZone==='JHR03' ? 'selected' : '' }}>JHR03 - Kluang, Pontian</option>
                                        <option value="JHR04" {{ $selectedZone==='JHR04' ? 'selected' : '' }}>JHR04 - Batu Pahat, Muar, Segamat, Gemas Johor, Tangkak</option>
                                    </optgroup>
                                    <optgroup label="Kedah">
                                        <option value="KDH01" {{ $selectedZone==='KDH01' ? 'selected' : '' }}>KDH01 - Kota Setar, Kubang Pasu, Pokok Sena (Daerah Kecil)</option>
                                        <option value="KDH02" {{ $selectedZone==='KDH02' ? 'selected' : '' }}>KDH02 - Kuala Muda, Yan, Pendang</option>
                                        <option value="KDH03" {{ $selectedZone==='KDH03' ? 'selected' : '' }}>KDH03 - Padang Terap, Sik</option>
                                        <option value="KDH04" {{ $selectedZone==='KDH04' ? 'selected' : '' }}>KDH04 - Baling</option>
                                        <option value="KDH05" {{ $selectedZone==='KDH05' ? 'selected' : '' }}>KDH05 - Bandar Baharu, Kulim</option>
                                        <option value="KDH06" {{ $selectedZone==='KDH06' ? 'selected' : '' }}>KDH06 - Langkawi</option>
                                        <option value="KDH07" {{ $selectedZone==='KDH07' ? 'selected' : '' }}>KDH07 - Puncak Gunung Jerai</option>
                                    </optgroup>
                                    <optgroup label="Kelantan">
                                        <option value="KTN01" {{ $selectedZone==='KTN01' ? 'selected' : '' }}>KTN01 - Bachok, Kota Bharu, Machang, Pasir Mas, Pasir Puteh, Tanah Merah, Tumpat, Kuala Krai, Mukim Chiku</option>
                                        <option value="KTN02" {{ $selectedZone==='KTN02' ? 'selected' : '' }}>KTN02 - Gua Musang (Daerah Galas Dan Bertam), Jeli, Jajahan Kecil Lojing</option>
                                    </optgroup>
                                    <optgroup label="Melaka">
                                        <option value="MLK01" {{ $selectedZone==='MLK01' ? 'selected' : '' }}>MLK01 - SELURUH NEGERI MELAKA</option>
                                    </optgroup>
                                    <optgroup label="Negeri Sembilan">
                                        <option value="NGS01" {{ $selectedZone==='NGS01' ? 'selected' : '' }}>NGS01 - Tampin, Jempol</option>
                                        <option value="NGS02" {{ $selectedZone==='NGS02' ? 'selected' : '' }}>NGS02 - Jelebu, Kuala Pilah, Rembau</option>
                                        <option value="NGS03" {{ $selectedZone==='NGS03' ? 'selected' : '' }}>NGS03 - Port Dickson, Seremban</option>
                                    </optgroup>
                                    <optgroup label="Pahang">
                                        <option value="PHG01" {{ $selectedZone==='PHG01' ? 'selected' : '' }}>PHG01 - Pulau Tioman</option>
                                        <option value="PHG02" {{ $selectedZone==='PHG02' ? 'selected' : '' }}>PHG02 - Kuantan, Pekan, Muadzam Shah</option>
                                        <option value="PHG03" {{ $selectedZone==='PHG03' ? 'selected' : '' }}>PHG03 - Jerantut, Temerloh, Maran, Bera, Chenor, Jengka</option>
                                        <option value="PHG04" {{ $selectedZone==='PHG04' ? 'selected' : '' }}>PHG04 - Bentong, Lipis, Raub</option>
                                        <option value="PHG05" {{ $selectedZone==='PHG05' ? 'selected' : '' }}>PHG05 - Genting Sempah, Janda Baik, Bukit Tinggi</option>
                                        <option value="PHG06" {{ $selectedZone==='PHG06' ? 'selected' : '' }}>PHG06 - Cameron Highlands, Genting Higlands, Bukit Fraser</option>
                                        <option value="PHG07" {{ $selectedZone==='PHG07' ? 'selected' : '' }}>PHG07 - Zon Khas Daerah Rompin, (Mukim Rompin, Mukim Endau, Mukim Pontian)</option>
                                    </optgroup>
                                    <optgroup label="Perlis">
                                        <option value="PLS01" {{ $selectedZone==='PLS01' ? 'selected' : '' }}>PLS01 - Kangar, Padang Besar, Arau</option>
                                    </optgroup>
                                    <optgroup label="Pulau Pinang">
                                        <option value="PNG01" {{ $selectedZone==='PNG01' ? 'selected' : '' }}>PNG01 - Seluruh Negeri Pulau Pinang</option>
                                    </optgroup>
                                    <optgroup label="Perak">
                                        <option value="PRK01" {{ $selectedZone==='PRK01' ? 'selected' : '' }}>PRK01 - Tapah, Slim River, Tanjung Malim</option>
                                        <option value="PRK02" {{ $selectedZone==='PRK02' ? 'selected' : '' }}>PRK02 - Kuala Kangsar, Sg. Siput , Ipoh, Batu Gajah, Kampar</option>
                                        <option value="PRK03" {{ $selectedZone==='PRK03' ? 'selected' : '' }}>PRK03 - Lenggong, Pengkalan Hulu, Grik</option>
                                        <option value="PRK04" {{ $selectedZone==='PRK04' ? 'selected' : '' }}>PRK04 - Temengor, Belum</option>
                                        <option value="PRK05" {{ $selectedZone==='PRK05' ? 'selected' : '' }}>PRK05 - Kg Gajah, Teluk Intan, Bagan Datuk, Seri Iskandar, Beruas, Parit, Lumut, Sitiawan, Pulau Pangkor</option>
                                        <option value="PRK06" {{ $selectedZone==='PRK06' ? 'selected' : '' }}>PRK06 - Selama, Taiping, Bagan Serai, Parit Buntar</option>
                                        <option value="PRK07" {{ $selectedZone==='PRK07' ? 'selected' : '' }}>PRK07 - Bukit Larut</option>
                                    </optgroup>
                                    <optgroup label="Sabah">
                                        <option value="SBH01" {{ $selectedZone==='SBH01' ? 'selected' : '' }}>SBH01 - Bahagian Sandakan (Timur)...</option>
                                        <option value="SBH02" {{ $selectedZone==='SBH02' ? 'selected' : '' }}>SBH02 - Beluran, Telupid, Pinangah...</option>
                                        <option value="SBH03" {{ $selectedZone==='SBH03' ? 'selected' : '' }}>SBH03 - Lahad Datu, Silabukan, Kunak...</option>
                                        <option value="SBH04" {{ $selectedZone==='SBH04' ? 'selected' : '' }}>SBH04 - Bandar Tawau, Balong, Merotai...</option>
                                        <option value="SBH05" {{ $selectedZone==='SBH05' ? 'selected' : '' }}>SBH05 - Kudat, Kota Marudu, Pitas...</option>
                                        <option value="SBH06" {{ $selectedZone==='SBH06' ? 'selected' : '' }}>SBH06 - Gunung Kinabalu</option>
                                        <option value="SBH07" {{ $selectedZone==='SBH07' ? 'selected' : '' }}>SBH07 - Kota Kinabalu, Ranau, Kota Belud...</option>
                                        <option value="SBH08" {{ $selectedZone==='SBH08' ? 'selected' : '' }}>SBH08 - Pensiangan, Keningau, Tambunan...</option>
                                        <option value="SBH09" {{ $selectedZone==='SBH09' ? 'selected' : '' }}>SBH09 - Beaufort, Kuala Penyu, Sipitang...</option>
                                    </optgroup>
                                    <optgroup label="Selangor">
                                        <option value="SGR01" {{ $selectedZone==='SGR01' ? 'selected' : '' }}>SGR01 - Gombak, Petaling, Sepang, Hulu Langat, Hulu Selangor, S.Alam</option>
                                        <option value="SGR02" {{ $selectedZone==='SGR02' ? 'selected' : '' }}>SGR02 - Kuala Selangor, Sabak Bernam</option>
                                        <option value="SGR03" {{ $selectedZone==='SGR03' ? 'selected' : '' }}>SGR03 - Klang, Kuala Langat</option>
                                    </optgroup>
                                    <optgroup label="Sarawak">
                                        <option value="SWK01" {{ $selectedZone==='SWK01' ? 'selected' : '' }}>SWK01 - Limbang, Lawas, Sundar, Trusan</option>
                                        <option value="SWK02" {{ $selectedZone==='SWK02' ? 'selected' : '' }}>SWK02 - Miri, Niah, Bekenu, Sibuti, Marudi</option>
                                        <option value="SWK03" {{ $selectedZone==='SWK03' ? 'selected' : '' }}>SWK03 - Pandan, Belaga, Suai, Tatau, Sebauh, Bintulu</option>
                                        <option value="SWK04" {{ $selectedZone==='SWK04' ? 'selected' : '' }}>SWK04 - Sibu, Mukah, Dalat, Song, Igan, Oya, Balingian, Kanowit, Kapit</option>
                                        <option value="SWK05" {{ $selectedZone==='SWK05' ? 'selected' : '' }}>SWK05 - Sarikei, Matu, Julau, Rajang, Daro, Bintangor, Belawai</option>
                                        <option value="SWK06" {{ $selectedZone==='SWK06' ? 'selected' : '' }}>SWK06 - Lubok Antu, Sri Aman, Roban, Debak, Kabong, Lingga, Engkelili, Betong, Spaoh, Pusa, Saratok</option>
                                        <option value="SWK07" {{ $selectedZone==='SWK07' ? 'selected' : '' }}>SWK07 - Serian, Simunjan, Samarahan, Sebuyau, Meludam</option>
                                        <option value="SWK08" {{ $selectedZone==='SWK08' ? 'selected' : '' }}>SWK08 - Kuching, Bau, Lundu, Sematan</option>
                                        <option value="SWK09" {{ $selectedZone==='SWK09' ? 'selected' : '' }}>SWK09 - Zon Khas (Kampung Patarikan)</option>
                                    </optgroup>
                                    <optgroup label="Terengganu">
                                        <option value="TRG01" {{ $selectedZone==='TRG01' ? 'selected' : '' }}>TRG01 - Kuala Terengganu, Marang, Kuala Nerus</option>
                                        <option value="TRG02" {{ $selectedZone==='TRG02' ? 'selected' : '' }}>TRG02 - Besut, Setiu</option>
                                        <option value="TRG03" {{ $selectedZone==='TRG03' ? 'selected' : '' }}>TRG03 - Hulu Terengganu</option>
                                        <option value="TRG04" {{ $selectedZone==='TRG04' ? 'selected' : '' }}>TRG04 - Dungun, Kemaman</option>
                                    </optgroup>
                                    <optgroup label="Wilayah Persekutuan">
                                        <option value="WLY01" {{ $selectedZone==='WLY01' ? 'selected' : '' }}>WLY01 - Kuala Lumpur, Putrajaya</option>
                                        <option value="WLY02" {{ $selectedZone==='WLY02' ? 'selected' : '' }}>WLY02 - Labuan</option>
                                    </optgroup>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Pilih zon JAKIM untuk paparan Waktu Solat di topbar.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sistem Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="material-icons text-sm mr-2 text-green-600">computer</span>
                            Tetapan Sistem
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Max Login Attempts -->
                            <div>
                                <label for="max_login_attempts" class="block text-xs font-medium text-gray-700 mb-2">Maksimum Percubaan Login</label>
                                <input type="number" 
                                       id="max_login_attempts" 
                                       name="tetapan[max_login_attempts]" 
                                       value="{{ $tetapan->where('kunci', 'max_login_attempts')->first()?->nilai ?? '5' }}"
                                       min="1" max="10"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Bilangan maksimum percubaan login sebelum account dikunci</p>
                            </div>

                            <!-- Session Timeout -->
                            <div>
                                <label for="session_timeout" class="block text-xs font-medium text-gray-700 mb-2">Masa Tamat Sesi (minit)</label>
                                <input type="number" 
                                       id="session_timeout" 
                                       name="tetapan[session_timeout]" 
                                       value="{{ $tetapan->where('kunci', 'session_timeout')->first()?->nilai ?? '120' }}"
                                       min="30" max="480"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">Masa dalam minit sebelum sesi tamat</p>
                            </div>
                        </div>
                    </div>

                    <!-- reCAPTCHA Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="material-icons text-sm mr-2 text-purple-600">security</span>
                            Tetapan reCAPTCHA
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Enable reCAPTCHA -->
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="tetapan[recaptcha_enabled]" 
                                           value="1"
                                           {{ ($tetapan->where('kunci', 'recaptcha_enabled')->first()?->nilai == '1' ? true : false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-xs font-medium text-gray-700">Aktifkan reCAPTCHA untuk Feedback Form</span>
                                </label>
                                <p class="mt-1 text-xs text-gray-500">Aktifkan atau nyahaktifkan reCAPTCHA untuk melindungi feedback form dari spam</p>
                            </div>

                            <!-- reCAPTCHA Site Key -->
                            <div>
                                <label for="recaptcha_site_key" class="block text-xs font-medium text-gray-700 mb-2">reCAPTCHA Site Key</label>
                                <input type="text" 
                                       id="recaptcha_site_key" 
                                       name="tetapan[recaptcha_site_key]" 
                                       value="{{ $tetapan->where('kunci', 'recaptcha_site_key')->first()?->nilai ?? '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="6Lxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                                <p class="mt-1 text-xs text-gray-500">Public key dari Google reCAPTCHA Console</p>
                            </div>

                            <!-- reCAPTCHA Secret Key -->
                            <div>
                                <label for="recaptcha_secret_key" class="block text-xs font-medium text-gray-700 mb-2">reCAPTCHA Secret Key</label>
                                <input type="password" 
                                       id="recaptcha_secret_key" 
                                       name="tetapan[recaptcha_secret_key]" 
                                       value="{{ $tetapan->where('kunci', 'recaptcha_secret_key')->first()?->nilai ?? '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="6Lxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                                <p class="mt-1 text-xs text-gray-500">Private key dari Google reCAPTCHA Console</p>
                            </div>

                            <!-- reCAPTCHA Info -->
                            <div class="md:col-span-2">
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                                    <div class="flex">
                                        <span class="material-icons text-blue-400 text-sm mr-2">info</span>
                                        <div class="text-xs text-blue-800">
                                            <p class="font-medium mb-1">Cara Setup reCAPTCHA:</p>
                                            <ol class="list-decimal list-inside space-y-1">
                                                <li>Pergi ke <a href="https://www.google.com/recaptcha/admin" target="_blank" class="underline">Google reCAPTCHA Console</a></li>
                                                <li>Pilih "reCAPTCHA v2" → "Invisible reCAPTCHA badge"</li>
                                                <li>Tambah domain: <code class="bg-blue-100 px-1 rounded">localhost:8000</code>, <code class="bg-blue-100 px-1 rounded">localhost:8080</code></li>
                                                <li>Copy Site Key dan Secret Key ke sini</li>
                                                <li>Aktifkan reCAPTCHA dan simpan tetapan</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="material-icons text-sm mr-2 text-orange-600">notifications</span>
                            Tetapan Notifikasi
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Notify New User -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="tetapan[notify_new_user]" 
                                           value="1"
                                           {{ ($tetapan->where('kunci', 'notify_new_user')->first()?->nilai ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-xs font-medium text-gray-700">Notifikasi Pengguna Baru</span>
                                </label>
                                <p class="mt-1 text-xs text-gray-500">Hantar notifikasi apabila ada pengguna baru</p>
                            </div>

                            <!-- Notify Login Failed -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="tetapan[notify_login_failed]" 
                                           value="1"
                                           {{ ($tetapan->where('kunci', 'notify_login_failed')->first()?->nilai ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-xs font-medium text-gray-700">Notifikasi Login Gagal</span>
                                </label>
                                <p class="mt-1 text-xs text-gray-500">Hantar notifikasi apabila ada percubaan login gagal</p>
                            </div>

                            <!-- Notify System Error -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="tetapan[notify_system_error]" 
                                           value="1"
                                           {{ ($tetapan->where('kunci', 'notify_system_error')->first()?->nilai ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-xs font-medium text-gray-700">Notifikasi Ralat Sistem</span>
                                </label>
                                <p class="mt-1 text-xs text-gray-500">Hantar notifikasi apabila ada ralat sistem</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('overview') }}" class="px-6 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 flex items-center">
                            <span class="material-icons text-sm mr-2">save</span>
                            Simpan Tetapan
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
    </style>
</body>
</html>
