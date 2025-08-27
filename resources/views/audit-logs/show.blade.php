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
                    <h1 class="text-xl font-semibold text-gray-900 mb-1">Lihat Log Audit</h1>
                    <p class="text-xs text-gray-600">Maklumat terperinci aktiviti audit</p>
                </div>

                <!-- Activity Information -->
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3">Maklumat Aktiviti</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Event Type</label>
                                <div class="text-sm text-gray-900">
                                    @if($activity->event === 'created')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="material-icons text-[8px] mr-1">add</span>
                                            Dicipta
                                        </span>
                                    @elseif($activity->event === 'updated')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <span class="material-icons text-[8px] mr-1">edit</span>
                                            Dikemaskini
                                        </span>
                                    @elseif($activity->event === 'deleted')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="material-icons text-[8px] mr-1">delete</span>
                                            Dipadamkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($activity->event) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori</label>
                                <div class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $activity->log_name ?? 'System' }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Dicipta Pada</label>
                                <div class="text-xs text-gray-900 font-normal">{{ $activity->created_at->format('d/m/Y H:i:s') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Masa Lalu</label>
                                <div class="text-xs text-gray-900 font-normal">{{ $activity->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3">Keterangan</h2>
                        <div class="text-xs text-gray-900 font-normal">{{ $activity->description }}</div>
                    </div>
                </div>

                <!-- User Information -->
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3">Maklumat Pengguna</h2>
                        @if($activity->causer)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama</label>
                                    <div class="text-xs text-gray-900 font-normal">{{ $activity->causer->name }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                                    <div class="text-xs text-gray-900 font-normal">{{ $activity->causer->email }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Telefon</label>
                                    <div class="text-xs text-gray-900 font-normal">{{ $activity->causer->phone ?? '-' }}</div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">ID Pengguna</label>
                                    <div class="text-xs text-gray-900 font-normal">{{ $activity->causer->id }}</div>
                                </div>
                            </div>
                        @else
                            <div class="text-xs text-gray-500 font-normal">Aktiviti ini dilakukan oleh sistem</div>
                        @endif
                    </div>
                </div>

                <!-- Subject Information -->
                @if($activity->subject_type)
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3">Maklumat Objek</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Model</label>
                                <div class="text-xs text-gray-900 font-normal">{{ class_basename($activity->subject_type) }}</div>
                            </div>
                            <div>
                                @if($activity->subject && $activity->subject_type === 'App\Models\User')
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pengguna</label>
                                    <div class="text-xs text-gray-900 font-normal">{{ $activity->subject->name }}</div>
                                @else
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">ID Objek</label>
                                    <div class="text-xs text-gray-900 font-normal">{{ $activity->subject_id }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Properties -->
                @if($activity->properties->count() > 0)
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3">Properties</h2>
                        <div class="bg-white rounded-xs border border-gray-200 p-3">
                            <pre class="text-xs text-gray-900 overflow-x-auto">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Technical Details -->
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3">Maklumat Teknikal</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">IP Address</label>
                                <div class="text-xs text-gray-900 font-normal">{{ $activity->properties->get('ip_address', '-') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">User Agent</label>
                                <div class="text-xs text-gray-900 font-normal">{{ $activity->properties->get('user_agent', '-') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">URL</label>
                                <div class="text-xs text-gray-900 font-normal">{{ $activity->properties->get('url', '-') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Method</label>
                                <div class="text-xs text-gray-900 font-normal">{{ $activity->properties->get('method', '-') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('audit-logs.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs rounded-xs hover:bg-gray-200 h-8 flex items-center">
                        <span class="material-icons text-[10px] mr-2">arrow_back</span>
                        Kembali
                    </a>
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
</body>
</html>
