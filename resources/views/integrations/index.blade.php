<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Integrasi - Sistem Pengurusan Jenazah' }}</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">
    <x-double-navbar :user="$user" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Page Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Integrasi</h1>
                    <p class="text-xs text-gray-600">Senarai integrasi sistem dengan platform luar</p>
                </div>
            
            <!-- Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex flex-col sm:flex-row sm:space-x-8 space-y-2 sm:space-y-0">
                        <button onclick="showTab('email')" id="tab-email" class="tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-xs text-blue-600 flex items-center justify-center sm:justify-start">
                            <span class="material-icons text-xs mr-2">email</span>
                            Email (SMTP)
                        </button>

                        <button onclick="showTab('weather')" id="tab-weather" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs text-gray-500 flex items-center justify-center sm:justify-start">
                            <span class="material-icons text-xs mr-2">wb_sunny</span>
                            Cuaca
                        </button>
                        <button onclick="showTab('api')" id="tab-api" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs text-gray-500 flex items-center justify-center sm:justify-start">
                            <span class="material-icons text-xs mr-2">api</span>
                            API
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Tab Content -->
            <div id="tab-email-content" class="tab-content active">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Konfigurasi Email SMTP</h3>
                    <p class="text-xs text-gray-500 mb-4">Konfigurasi SMTP untuk sistem email</p>
                    
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-4">
                            <!-- SMTP Host -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">SMTP Host</label>
                                <input type="text" id="email-smtp-host" value="{{ $emailConfig->smtp_host }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- SMTP Port -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">SMTP Port</label>
                                <input type="number" id="email-smtp-port" value="{{ $emailConfig->smtp_port }}" min="1" max="65535" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Username/Email -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Username/Email</label>
                                <input type="email" id="email-username" value="{{ $emailConfig->username }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Password -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Password</label>
                                <input type="password" id="email-password" value="{{ $emailConfig->password ? '••••••••••••••••' : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Encryption -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Encryption</label>
                                <select id="email-encryption" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                    <option value="TLS" {{ $emailConfig->encryption == 'TLS' ? 'selected' : '' }}>TLS</option>
                                    <option value="SSL" {{ $emailConfig->encryption == 'SSL' ? 'selected' : '' }}>SSL</option>
                                    <option value="None" {{ $emailConfig->encryption == 'None' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            
                            <!-- Authentication -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Authentication</label>
                                <select id="email-authentication" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                    <option value="Required" {{ $emailConfig->authentication == 'Required' ? 'selected' : '' }}>Required</option>
                                    <option value="None" {{ $emailConfig->authentication == 'None' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            
                            <!-- From Name -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">From Name</label>
                                <input type="text" id="email-from-name" value="{{ $emailConfig->from_name }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Reply To -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Reply To</label>
                                <input type="email" id="email-reply-to" value="{{ $emailConfig->reply_to }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Connection Timeout -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Connection Timeout</label>
                                <input type="number" id="email-timeout" value="{{ $emailConfig->connection_timeout }}" min="1" max="300" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Max Retries -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Max Retries</label>
                                <input type="number" id="email-max-retries" value="{{ $emailConfig->max_retries }}" min="0" max="10" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Last Test -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Last Test</label>
                                <input type="text" value="{{ $emailConfig->formatted_last_test }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Test Status -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Test Status</label>
                                <input type="text" value="{{ $emailConfig->status_badge }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-4">
                            <button type="button" id="email-edit-btn" onclick="toggleEmailEdit()" class="action-button px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 font-medium flex items-center justify-center sm:justify-start">
                                <span class="material-icons text-xs mr-2">edit</span>
                                Edit Konfigurasi
                            </button>
                            <button type="button" id="email-save-btn" onclick="saveEmailConfig()" class="action-button px-4 py-2 bg-green-600 text-white text-xs rounded-xs hover:bg-green-700 font-medium hidden flex items-center justify-center sm:justify-start">
                                <span class="material-icons text-xs mr-2">save</span>
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="showTestEmailModal()" class="action-button px-4 py-2 bg-yellow-600 text-white text-xs rounded-xs hover:bg-yellow-700 font-medium flex items-center justify-center sm:justify-start">
                                <span class="material-icons text-xs mr-2">send</span>
                                Test Email
                            </button>
                            <button type="button" onclick="smtpHealth()" class="action-button px-4 py-2 bg-emerald-600 text-white text-xs rounded-xs hover:bg-emerald-700 font-medium flex items-center justify-center sm:justify-start">
                                <span class="material-icons text-xs mr-2">stay_primary_portrait</span>
                                SMTP Health
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="tab-api-content" class="tab-content">
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Konfigurasi API</h3>
                    <p class="text-xs text-gray-500 mb-4">Konfigurasi API untuk sistem integrasi</p>
                    
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Base URL -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Base URL</label>
                                <input type="text" id="api-base-url" value="{{ $apiConfig->base_url }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- API Version -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">API Version</label>
                                <input type="text" id="api-version" value="{{ $apiConfig->version }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            <!-- Rate Limit -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Rate Limit</label>
                                <select id="api-rate-limit" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                    <option value="{{ (int) $apiConfig->rate_limit }}" selected>{{ $apiConfig->rate_limit == 0 ? 'Unlimited' : $apiConfig->rate_limit . ' requests/hour' }}</option>
                                    <option value="100" {{ (int) $apiConfig->rate_limit == 100 ? 'selected' : '' }}>100 requests/hour</option>
                                    <option value="500" {{ (int) $apiConfig->rate_limit == 500 ? 'selected' : '' }}>500 requests/hour</option>
                                    <option value="1000" {{ (int) $apiConfig->rate_limit == 1000 ? 'selected' : '' }}>1000 requests/hour</option>
                                    <option value="0" {{ (int) $apiConfig->rate_limit == 0 ? 'selected' : '' }}>Unlimited</option>
                                </select>
                            </div>
                            
                            <!-- Timeout -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Timeout</label>
                                <select id="api-timeout" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                    <option value="{{ (int) $apiConfig->timeout }}" selected>{{ (int) $apiConfig->timeout }} saat</option>
                                    <option value="5" {{ (int) $apiConfig->timeout == 5 ? 'selected' : '' }}>5 saat</option>
                                    <option value="10" {{ (int) $apiConfig->timeout == 10 ? 'selected' : '' }}>10 saat</option>
                                    <option value="15" {{ (int) $apiConfig->timeout == 15 ? 'selected' : '' }}>15 saat</option>
                                    <option value="30" {{ (int) $apiConfig->timeout == 30 ? 'selected' : '' }}>30 saat</option>
                                    <option value="60" {{ (int) $apiConfig->timeout == 60 ? 'selected' : '' }}>60 saat</option>
                                </select>
                            </div>
                            
                            <!-- Max Retries -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Max Retries</label>
                                <input type="text" id="api-max-retries" value="{{ $apiConfig->max_retries }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- SSL Verification -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">SSL Verification</label>
                                <input type="text" id="api-ssl-verification" value="{{ $apiConfig->ssl_verification }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Logging Level -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Logging Level</label>
                                <select id="api-logging-level" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                    <option value="Error" {{ $apiConfig->logging_level == 'Error' ? 'selected' : '' }}>Error</option>
                                    <option value="Warn" {{ $apiConfig->logging_level == 'Warn' ? 'selected' : '' }}>Warn</option>
                                    <option value="Info" {{ $apiConfig->logging_level == 'Info' ? 'selected' : '' }}>Info</option>
                                    <option value="Debug" {{ $apiConfig->logging_level == 'Debug' ? 'selected' : '' }}>Debug</option>
                                </select>
                            </div>
                            
                            
                        </div>
                        
                        <!-- Sanctum Settings -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Laravel Sanctum</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Use Sanctum (read-only info for now) -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Authentication</label>
                                    <input type="text" id="api-auth-provider" value="Bearer Token (Laravel Sanctum)" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                </div>
                                
                                <!-- Token Name -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Token Name</label>
                                    <input type="text" id="api-token-name" value="{{ $latestToken->name ?? '' }}" placeholder="Contoh: public_website" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                    <p class="text-[10px] text-gray-500 mt-1">Diambil daripada jadual personal_access_tokens (token terkini pengguna).</p>
                                </div>
                                
                                <!-- Abilities -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Abilities (Scopes)</label>
                                    @php
                                        $selectedAbilities = is_string($apiConfig->default_abilities ?? null)
                                            ? (json_decode($apiConfig->default_abilities, true) ?: [])
                                            : ($apiConfig->default_abilities ?? []);
                                        $abilitiesOptions = [
                                            // Dashboard
                                            'read:overview' => 'read:overview',
                                            // Pengurusan
                                            'read:kematian' => 'read:kematian',
                                            'write:kematian' => 'write:kematian',
                                            'read:ppjub' => 'read:ppjub',
                                            'write:ppjub' => 'write:ppjub',
                                            // Pentadbiran Sistem
                                            'read:tetapan' => 'read:tetapan',
                                            'write:tetapan' => 'write:tetapan',
                                            'read:integrations' => 'read:integrations',
                                            'write:integrations' => 'write:integrations',
                                            'read:roles' => 'read:roles',
                                            'write:roles' => 'write:roles',
                                            'read:user-access' => 'read:user-access',
                                            'write:user-access' => 'write:user-access',
                                            'read:audit-logs' => 'read:audit-logs',
                                            'clear:audit-logs' => 'clear:audit-logs',
                                            // Bantuan & Halaman maklumat
                                            'read:system-status' => 'read:system-status',
                                            'read:faq' => 'read:faq',
                                            'read:user-guide' => 'read:user-guide',
                                            'read:release-notes' => 'read:release-notes',
                                            // Profil
                                            'read:profile' => 'read:profile',
                                            'write:profile' => 'write:profile',
                                            // Admin
                                            'admin:all' => 'admin:all',
                                        ];
                                    @endphp
                                    <select id="api-abilities" multiple size="10" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                        @foreach($abilitiesOptions as $value => $label)
                                            <option value="{{ $value }}" {{ in_array($value, $selectedAbilities ?? []) ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[10px] text-gray-500 mt-1">Pilih satu atau lebih abilities.</p>
                                </div>
                                
                                <!-- Token Expiry -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Token Expiry</label>
                                    <select id="api-token-expiry" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                        <option value="15m" {{ ($apiConfig->token_default_expiry ?? '') == '15m' ? 'selected' : '' }}>15 minit</option>
                                        <option value="1h" {{ ($apiConfig->token_default_expiry ?? '') == '1h' ? 'selected' : '' }}>1 jam</option>
                                        <option value="6h" {{ ($apiConfig->token_default_expiry ?? '6h') == '6h' ? 'selected' : '' }}>6 jam</option>
                                        <option value="24h" {{ ($apiConfig->token_default_expiry ?? '') == '24h' ? 'selected' : '' }}>24 jam</option>
                                        <option value="7d" {{ ($apiConfig->token_default_expiry ?? '') == '7d' ? 'selected' : '' }}>7 hari</option>
                                        <option value="never" {{ ($apiConfig->token_default_expiry ?? '') == 'never' ? 'selected' : '' }}>Tiada tamat tempoh</option>
                                    </select>
                                </div>
                                
                                <!-- Allowed Origins -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Allowed Origins (CORS)</label>
                                    <textarea id="api-allowed-origins" rows="2" placeholder="https://www.ppjub.my, https://ppjub.com.my" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>{{ $apiConfig->allowed_origins ?? '' }}</textarea>
                                    <p class="text-[10px] text-gray-500 mt-1">Pisahkan dengan koma.</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-4">
                                <button type="button" id="sanctum-generate-btn" onclick="generateSanctumToken()" class="hidden-important action-button px-4 py-2 bg-emerald-600 text-white text-xs rounded-xs hover:bg-emerald-700 font-medium flex items-center justify-center sm:justify-start">
                                    <span class="material-icons text-xs mr-2">key</span>
                                    Generate Token
                                </button>
                                <button type="button" id="sanctum-revoke-btn" onclick="revokeAllTokens()" class="hidden-important action-button px-4 py-2 bg-rose-600 text-white text-xs rounded-xs hover:bg-rose-700 font-medium flex items-center justify-center sm:justify-start">
                                    <span class="material-icons text-xs mr-2">delete</span>
                                    Revoke All Tokens
                                </button>
                            </div>
                            
                            <!-- Token List Placeholder -->
                            <div class="mt-4">
                                <h5 class="text-xs font-medium text-gray-800 mb-2">Tokens</h5>
                                <div id="api-token-list" class="text-[11px] text-gray-600">Belum ada token.</div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-4">
                            <button type="button" id="api-edit-btn" onclick="toggleApiEdit()" class="action-button px-4 py-2 bg-green-600 text-white text-xs rounded-xs hover:bg-green-700 font-medium flex items-center justify-center sm:justify-start">
                                <span class="material-icons text-xs mr-2">edit</span>
                                Edit Konfigurasi
                            </button>
                            <button type="button" id="api-save-btn" onclick="saveApiConfig()" class="action-button px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 font-medium hidden flex items-center justify-center sm:justify-start">
                                <span class="material-icons text-xs mr-2">save</span>
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="testApiConnection()" class="action-button px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 font-medium">
                                <span class="material-icons text-xs mr-2">api</span>
                                Test API
                            </button>
                            <button class="action-button px-4 py-2 bg-purple-600 text-white text-xs rounded-xs hover:bg-purple-700 font-medium">
                                <span class="material-icons text-xs mr-2">sync</span>
                                Sync Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="tab-webhook-content" class="tab-content">
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Konfigurasi Webhook</h3>
                    <p class="text-xs text-gray-500 mb-4">Konfigurasi webhook untuk sistem notifikasi</p>
                    
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Webhook URL -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Webhook URL</label>
                                <input type="text" value="https://webhook.ekubur.com/notify" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Secret Key -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Secret Key</label>
                                <input type="password" value="••••••••••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- HTTP Method -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">HTTP Method</label>
                                <input type="text" value="POST" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Content Type -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Content Type</label>
                                <input type="text" value="application/json" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Timeout -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Timeout</label>
                                <input type="text" value="10 saat" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Max Retries -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Max Retries</label>
                                <input type="text" value="3" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Retry Delay -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Retry Delay</label>
                                <input type="text" value="5 saat" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- SSL Verification -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">SSL Verification</label>
                                <input type="text" value="Enabled" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Headers -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Custom Headers</label>
                                <input type="text" value="X-API-Key: webhook_key" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Last Trigger -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Last Trigger</label>
                                <input type="text" value="5 minit lalu" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                                <input type="text" value="Active" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Success Rate -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Success Rate</label>
                                <input type="text" value="98.5%" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button class="action-button px-4 py-2 bg-orange-600 text-white text-xs rounded-xs hover:bg-orange-700 font-medium">
                                <span class="material-icons text-xs mr-2">edit</span>
                                Edit Konfigurasi
                            </button>
                            <button class="action-button px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 font-medium">
                                <span class="material-icons text-xs mr-2">send</span>
                                Test Webhook
                            </button>
                            <button class="action-button px-4 py-2 bg-green-600 text-white text-xs rounded-xs hover:bg-green-700 font-medium">
                                <span class="material-icons text-xs mr-2">visibility</span>
                                View Logs
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="tab-sms-content" class="tab-content">
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Konfigurasi SMS</h3>
                    <p class="text-xs text-gray-500 mb-4">Konfigurasi SMS untuk sistem notifikasi</p>
                    
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Provider -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">SMS Provider</label>
                                <input type="text" value="Twilio" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Account SID -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Account SID</label>
                                <input type="text" value="AC1234567890abcdef" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Auth Token -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Auth Token</label>
                                <input type="password" value="••••••••••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- From Number -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">From Number</label>
                                <input type="text" value="+60123456789" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- API URL -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">API URL</label>
                                <input type="text" value="https://api.twilio.com/2010-04-01" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Timeout -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Timeout</label>
                                <input type="text" value="30 saat" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Max Retries -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Max Retries</label>
                                <input type="text" value="3" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Character Limit -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Character Limit</label>
                                <input type="text" value="160" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Delivery Reports -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Delivery Reports</label>
                                <input type="text" value="Enabled" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Last Sent -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Last Sent</label>
                                <input type="text" value="10 minit lalu" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                                <input type="text" value="Active" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Success Rate -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Success Rate</label>
                                <input type="text" value="99.2%" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button class="action-button px-4 py-2 bg-teal-600 text-white text-xs rounded-xs hover:bg-teal-700 font-medium">
                                <span class="material-icons text-xs mr-2">edit</span>
                                Edit Konfigurasi
                            </button>
                            <button class="action-button px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 font-medium">
                                <span class="material-icons text-xs mr-2">sms</span>
                                Test SMS
                            </button>
                            <button class="action-button px-4 py-2 bg-green-600 text-white text-xs rounded-xs hover:bg-green-700 font-medium">
                                <span class="material-icons text-xs mr-2">visibility</span>
                                View Logs
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="tab-payment-content" class="tab-content">
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Konfigurasi Payment Gateway</h3>
                    <p class="text-xs text-gray-500 mb-4">Konfigurasi payment gateway untuk sistem pembayaran</p>
                    
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Provider -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Payment Provider</label>
                                <input type="text" value="Stripe" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Environment -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Environment</label>
                                <input type="text" value="Production" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Publishable Key -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Publishable Key</label>
                                <input type="text" value="pk_live_1234567890abcdef" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Secret Key -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Secret Key</label>
                                <input type="password" value="••••••••••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Webhook Secret -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Webhook Secret</label>
                                <input type="password" value="••••••••••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Currency -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Currency</label>
                                <input type="text" value="MYR" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Minimum Amount -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Minimum Amount</label>
                                <input type="text" value="RM 1.00" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Maximum Amount -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Maximum Amount</label>
                                <input type="text" value="RM 10,000.00" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Processing Fee -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Processing Fee</label>
                                <input type="text" value="2.9% + RM 0.30" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Settlement Period -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Settlement Period</label>
                                <input type="text" value="2-3 hari kerja" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Last Transaction -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Last Transaction</label>
                                <input type="text" value="15 minit lalu" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                                <input type="text" value="Active" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button class="action-button px-4 py-2 bg-indigo-600 text-white text-xs rounded-xs hover:bg-indigo-700 font-medium">
                                <span class="material-icons text-xs mr-2">edit</span>
                                Edit Konfigurasi
                            </button>
                            <button class="action-button px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 font-medium">
                                <span class="material-icons text-xs mr-2">payment</span>
                                Test Payment
                            </button>
                            <button class="action-button px-4 py-2 bg-green-600 text-white text-xs rounded-xs hover:bg-green-700 font-medium">
                                <span class="material-icons text-xs mr-2">visibility</span>
                                View Transactions
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="tab-weather-content" class="tab-content">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Konfigurasi API Cuaca</h3>
                    <p class="text-xs text-gray-500 mb-4">Konfigurasi API cuaca untuk sistem maklumat</p>
                    
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Provider -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Weather Provider</label>
                                <select id="weather-provider" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                    <option value="OpenWeatherMap" {{ $weatherConfig->provider == 'OpenWeatherMap' ? 'selected' : '' }}>OpenWeatherMap</option>
                                    <option value="WeatherAPI" {{ $weatherConfig->provider == 'WeatherAPI' ? 'selected' : '' }}>WeatherAPI</option>
                                    <option value="AccuWeather" {{ $weatherConfig->provider == 'AccuWeather' ? 'selected' : '' }}>AccuWeather</option>
                                </select>
                            </div>
                            
                            <!-- API Key -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">API Key</label>
                                <input type="password" id="weather-api-key" value="{{ $weatherConfig->api_key ? '••••••••••••••••' : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Base URL -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Base URL</label>
                                <input type="url" id="weather-base-url" value="{{ $weatherConfig->base_url }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Default Location -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Default Location</label>
                                <input type="text" id="weather-location" value="{{ $weatherConfig->default_location }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Latitude -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Latitude</label>
                                <input type="number" id="weather-latitude" value="{{ $weatherConfig->latitude }}" step="0.0000001" min="-90" max="90" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Longitude -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Longitude</label>
                                <input type="number" id="weather-longitude" value="{{ $weatherConfig->longitude }}" step="0.0000001" min="-180" max="180" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Units -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Units</label>
                                <select id="weather-units" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                    <option value="metric" {{ $weatherConfig->units == 'metric' ? 'selected' : '' }}>Metric (Celsius)</option>
                                    <option value="imperial" {{ $weatherConfig->units == 'imperial' ? 'selected' : '' }}>Imperial (Fahrenheit)</option>
                                </select>
                            </div>
                            
                            <!-- Language -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Language</label>
                                <select id="weather-language" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                                    <option value="ms" {{ $weatherConfig->language == 'ms' ? 'selected' : '' }}>Bahasa Melayu</option>
                                    <option value="en" {{ $weatherConfig->language == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="zh" {{ $weatherConfig->language == 'zh' ? 'selected' : '' }}>中文</option>
                                    <option value="ta" {{ $weatherConfig->language == 'ta' ? 'selected' : '' }}>தமிழ்</option>
                                </select>
                            </div>
                            
                            <!-- Update Frequency -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Update Frequency</label>
                                <input type="number" id="weather-update-freq" value="{{ $weatherConfig->update_frequency }}" min="1" max="1440" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Cache Duration -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Cache Duration</label>
                                <input type="number" id="weather-cache-duration" value="{{ $weatherConfig->cache_duration }}" min="1" max="1440" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" disabled>
                            </div>
                            
                            <!-- Last Update -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Last Update</label>
                                <input type="text" value="{{ $weatherConfig->formatted_last_update }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                            
                            <!-- Current Weather -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Current Weather</label>
                                <input type="text" id="weather-current-weather" value="{{ $weatherConfig->current_weather ?? 'Belum dikemas kini' }}" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs bg-gray-100" readonly>
                            </div>
                        </div>
                        

                        
                        <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-4">
                            <button type="button" id="weather-edit-btn" onclick="toggleWeatherEdit()" class="action-button px-4 py-2 bg-yellow-600 text-white text-xs rounded-xs hover:bg-yellow-700 font-medium flex items-center justify-center sm:justify-start">
                                <span class="material-icons text-xs mr-2">edit</span>
                                Edit Konfigurasi
                            </button>
                            <button type="button" id="weather-save-btn" onclick="saveWeatherConfig()" class="action-button px-4 py-2 bg-green-600 text-white text-xs rounded-xs hover:bg-green-700 font-medium hidden flex items-center justify-center sm:justify-start">
                                <span class="material-icons text-xs mr-2">save</span>
                                Simpan Perubahan
                            </button>
                            <button class="action-button px-4 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 font-medium flex items-center justify-center sm:justify-start">
                                <span class="material-icons text-xs mr-2">wb_sunny</span>
                                Test Weather
                            </button>
                            <button class="action-button px-4 py-2 bg-purple-600 text-white text-xs rounded-xs hover:bg-purple-700 font-medium flex items-center justify-center sm:justify-start">
                                <span class="material-icons text-xs mr-2">refresh</span>
                                Refresh Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </main>

    @include('components.footer')

    <!-- Test Email Modal -->
    <div id="testEmailModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3) !important;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">Test Email Configuration</h3>
                        <button onclick="hideTestEmailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <span class="material-icons text-xs">close</span>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="px-6 py-4">
                    <p class="text-xs text-gray-600 mb-4">Masukkan email address yang akan receive test email:</p>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email Penerima</label>
                        <input type="email" id="recipientEmail" placeholder="contoh@email.com" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Maklumat Konfigurasi</label>
                        <div class="bg-gray-50 p-3 rounded-xs text-xs">
                            <div class="grid grid-cols-2 gap-2">
                                <div><strong>SMTP Host:</strong> {{ $emailConfig->smtp_host }}</div>
                                <div><strong>Port:</strong> {{ $emailConfig->smtp_port }}</div>
                                <div><strong>Username:</strong> {{ $emailConfig->username }}</div>
                                <div><strong>Encryption:</strong> {{ $emailConfig->encryption }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button onclick="hideTestEmailModal()" class="px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xs hover:bg-gray-200">
                        Batal
                    </button>
                    <button onclick="testEmailConfig()" class="px-3 py-2 text-xs font-medium text-white bg-yellow-600 rounded-xs hover:bg-yellow-700 flex items-center">
                        <span class="material-icons text-xs mr-2">send</span>
                        Hantar Test Email
                    </button>
                </div>
            </div>
        </div>
    </div>

<style>
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .tab-button {
        cursor: pointer;
    }
    .tab-button.active {
        border-color: #3b82f6;
        color: #3b82f6;
    }
    
    /* Override Material Icons size for tab buttons */
    .tab-button .material-icons.text-xs {
        font-size: 18px !important;
        line-height: 1 !important;
    }
    
    /* Override Material Icons size for action buttons */
    .action-button .material-icons.text-xs {
        font-size: 16px !important;
        line-height: 1 !important;
    }
    
    /* Override global Material Icons CSS for action buttons */
    .action-button .material-icons {
        font-size: 16px !important;
        line-height: 1 !important;
    }
    
    /* Center align icons and text in action buttons */
    .action-button {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .action-button .material-icons {
        margin-right: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    /* Modal button styling */
    #testEmailModal button {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    #testEmailModal .material-icons {
        font-size: 12px !important;
        line-height: 1 !important;
        margin-right: 6px !important;
    }
    /* Ensure we can force-hide action buttons despite display:flex !important */
    .hidden-important { display: none !important; }
</style>

<script>
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active from all buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab
        document.getElementById(`tab-${tabName}-content`).classList.add('active');
        
        // Activate button
        const btn = document.getElementById(`tab-${tabName}`);
        btn.classList.add('active', 'border-blue-500', 'text-blue-600');
        btn.classList.remove('border-transparent', 'text-gray-500');
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        showTab('email');
        // Load tokens list on page load (requires auth session)
        fetch('{{ route("sanctum-tokens.index") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.ok ? r.json() : null)
        .then(data => {
            if (!data || !data.success) return;
            const tokenList = document.getElementById('api-token-list');
            if (!tokenList) return;
            if (!data.tokens || data.tokens.length === 0) {
                tokenList.textContent = 'Belum ada token.';
                return;
            }
            tokenList.innerHTML = data.tokens.map(t => {
                const abilities = Array.isArray(t.abilities) ? t.abilities.join(', ') : t.abilities;
                return `<div class="mb-2 p-2 border border-gray-200 rounded-xs bg-white"><div><strong>${t.name}</strong></div><div>Abilities: ${abilities}</div><div class="text-gray-500">Dicipta: ${t.created_at ?? '-'}</div><div class="text-gray-500">Diguna: ${t.last_used_at ?? '-'}</div></div>`;
            }).join('');
        }).catch(() => {});
    });
    
    // Toggle weather edit mode
    function toggleWeatherEdit() {
        const editBtn = document.getElementById('weather-edit-btn');
        const saveBtn = document.getElementById('weather-save-btn');
        const isEditing = editBtn.textContent.includes('Batal');
        
        // Only these fields can be edited
        const editableFields = [
            'weather-provider',
            'weather-api-key',
            'weather-base-url',
            'weather-location',
            'weather-units',
            'weather-language',
            'weather-update-freq',
            'weather-cache-duration'
        ];
        
        // These fields will be auto-updated based on location
        const autoUpdateFields = [
            'weather-latitude',
            'weather-longitude'
        ];
        
        if (!isEditing) {
            // Enable edit mode for editable fields only
            editableFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = false;
                    element.classList.remove('bg-gray-100');
                    element.classList.add('bg-white', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
                }
            });
            
            // Keep auto-update fields disabled
            autoUpdateFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = true;
                    element.classList.remove('bg-white', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
                    element.classList.add('bg-gray-100');
                }
            });
            
            // Change button text
            editBtn.innerHTML = '<span class="material-icons text-xs mr-2">close</span>Batal';
            editBtn.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
            editBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
            
            // Show save button
            saveBtn.classList.remove('hidden');
            
        } else {
            // Disable edit mode
            editableFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = true;
                    element.classList.remove('bg-white', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
                    element.classList.add('bg-gray-100');
                }
            });
            
            // Change button text back
            editBtn.innerHTML = '<span class="material-icons text-xs mr-2">edit</span>Edit Konfigurasi';
            editBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            editBtn.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
            
            // Hide save button
            saveBtn.classList.add('hidden');
        }
    }
    
    // Toggle API edit mode
    function toggleApiEdit() {
        const editBtn = document.getElementById('api-edit-btn');
        const saveBtn = document.getElementById('api-save-btn');
        const isEditing = editBtn.textContent.includes('Batal');

        const apiFields = [
            'api-base-url',
            'api-version',
            'api-rate-limit',
            'api-timeout',
            'api-max-retries',
            'api-ssl-verification',
            'api-logging-level',
            // Sanctum fields
            'api-token-name',
            'api-token-expiry',
            'api-allowed-origins',
            'api-abilities'
        ];

        if (!isEditing) {
            apiFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = false;
                    element.classList.remove('bg-gray-100');
                    element.classList.add('bg-white', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
                }
            });

            editBtn.innerHTML = '<span class="material-icons text-xs mr-2">close</span>Batal';
            editBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            editBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');

            saveBtn.classList.remove('hidden');

            // Show Sanctum action buttons in edit mode
            const genBtn = document.getElementById('sanctum-generate-btn');
            const revokeBtn = document.getElementById('sanctum-revoke-btn');
            if (genBtn) genBtn.classList.remove('hidden-important');
            if (revokeBtn) revokeBtn.classList.remove('hidden-important');
        } else {
            apiFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = true;
                    element.classList.remove('bg-white', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
                    element.classList.add('bg-gray-100');
                }
            });

            editBtn.innerHTML = '<span class="material-icons text-xs mr-2">edit</span>Edit Konfigurasi';
            editBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            editBtn.classList.add('bg-green-600', 'hover:bg-green-700');

            saveBtn.classList.add('hidden');

            // Hide Sanctum action buttons when not editing
            const genBtn = document.getElementById('sanctum-generate-btn');
            const revokeBtn = document.getElementById('sanctum-revoke-btn');
            if (genBtn) genBtn.classList.add('hidden-important');
            if (revokeBtn) revokeBtn.classList.add('hidden-important');
        }
    }

    // Save API configuration
    function saveApiConfig() {
        const saveBtn = event.target;
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<span class="material-icons text-xs mr-2">hourglass_empty</span>Menyimpan...';
        saveBtn.disabled = true;

        const formData = {
            base_url: document.getElementById('api-base-url').value,
            version: document.getElementById('api-version').value,
            // auth_type is fixed to Sanctum Bearer
            auth_type: 'Bearer Token',
            rate_limit: document.getElementById('api-rate-limit').value,
            timeout: document.getElementById('api-timeout').value,
            max_retries: document.getElementById('api-max-retries').value,
            ssl_verification: document.getElementById('api-ssl-verification').value,
            logging_level: document.getElementById('api-logging-level').value,
            // Sanctum config
            token_default_expiry: document.getElementById('api-token-expiry').value,
            allowed_origins: document.getElementById('api-allowed-origins').value,
            default_abilities: Array.from(document.getElementById('api-abilities').selectedOptions).map(o => o.value),
            token_name: document.getElementById('api-token-name').value
        };

        fetch('{{ route("api-configurations.update", 1) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                ...formData,
                _method: 'PUT'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Berjaya disimpan', 'success');
                toggleApiEdit();
            } else {
                showNotification(data.message || 'Ralat semasa menyimpan', 'error');
            }
        })
        .catch(error => {
            showNotification('Ralat semasa menyimpan: ' + error.message, 'error');
        })
        .finally(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    }

    // Placeholder: Generate token (UI only)
    function generateSanctumToken() {
        const name = document.getElementById('api-token-name').value.trim() || 'public_website';
        const expiry = document.getElementById('api-token-expiry').value;
        const selected = Array.from(document.getElementById('api-abilities').selectedOptions).map(o => o.value);

        fetch('{{ route("sanctum-tokens.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                token_name: name,
                abilities: selected,
                // Optional: convert expiry to minutes on backend later if needed
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.token) {
                // Show the token once
                const tokenList = document.getElementById('api-token-list');
                if (tokenList) {
                    tokenList.innerHTML = '<div class="px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xs text-[11px] text-gray-800">Token baharu:<br><strong>' + data.token + '</strong><br><span class="text-red-500">Simpan segera. Token tidak akan dipaparkan lagi.</span></div>';
                }
                showNotification('Token berjaya dijana.', 'success');
            } else {
                showNotification(data.message || 'Gagal jana token', 'error');
            }
        })
        .catch(err => showNotification('Ralat: ' + err.message, 'error'));
    }

    // Placeholder: Revoke all tokens (UI only)
    function revokeAllTokens() {
        fetch('{{ route("sanctum-tokens.destroy-all") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const tokenList = document.getElementById('api-token-list');
                if (tokenList) tokenList.innerHTML = 'Belum ada token.';
                showNotification('Semua token telah dibatalkan.', 'success');
            } else {
                showNotification(data.message || 'Gagal membatalkan token', 'error');
            }
        })
        .catch(err => showNotification('Ralat: ' + err.message, 'error'));
    }

    // Test API connectivity using Base URL and a simple endpoint (e.g., /health or /overview)
    function testApiConnection() {
        const btn = event.target.closest('button');
        const original = btn.innerHTML;
        btn.innerHTML = '<span class="material-icons text-xs mr-2">hourglass_empty</span>Menguji...';
        btn.disabled = true;

        const baseUrl = document.getElementById('api-base-url').value.trim();
        // Prefer app's /health if same-origin, else try baseUrl/health
        const sameOrigin = baseUrl.startsWith(window.location.origin);
        const testUrl = sameOrigin ? '{{ url('/health') }}' : baseUrl.replace(/\/$/, '') + '/health';

        fetch(testUrl, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (data && (data.ok || data.status === 'ok' || data.success)) {
                    showNotification('API OK (' + testUrl + ')', 'success');
                } else {
                    showNotification('API balas tapi format tidak dijangka (' + testUrl + ')', 'warning');
                }
            })
            .catch(err => showNotification('Gagal capai API: ' + err.message, 'error'))
            .finally(() => { btn.innerHTML = original; btn.disabled = false; });
    }

    // Save weather configuration
    function saveWeatherConfig() {
        // Show loading state
        const saveBtn = event.target;
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<span class="material-icons text-xs mr-2">hourglass_empty</span>Menyimpan...';
        saveBtn.disabled = true;
        
        // Collect form data
        const formData = {
            provider: document.getElementById('weather-provider').value,
            api_key: document.getElementById('weather-api-key').value || '{{ $weatherConfig->api_key }}',
            base_url: document.getElementById('weather-base-url').value,
            default_location: document.getElementById('weather-location').value,
            latitude: document.getElementById('weather-latitude').value,
            longitude: document.getElementById('weather-longitude').value,
            units: document.getElementById('weather-units').value,
            language: document.getElementById('weather-language').value,
            update_frequency: document.getElementById('weather-update-freq').value,
            cache_duration: document.getElementById('weather-cache-duration').value
        };
        
        // Make AJAX request
        fetch('{{ route("weather-configurations.update-ajax", 1) }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                // Exit edit mode
                toggleWeatherEdit();
            } else {
                showNotification(data.message || 'Ralat semasa menyimpan', 'error');
            }
        })
        .catch(error => {
            console.error('Weather update error:', error);
            showNotification('Ralat semasa menyimpan: ' + error.message, 'error');
        })
        .finally(() => {
            // Restore button state
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    }
    
    // Auto-update coordinates and weather when location changes
    function updateLocationData() {
        const locationInput = document.getElementById('weather-location');
        const latitudeInput = document.getElementById('weather-latitude');
        const longitudeInput = document.getElementById('weather-longitude');
        const currentWeatherInput = document.getElementById('weather-current-weather'); // Current weather field
        
        // Common Malaysian cities with coordinates
        const cityCoordinates = {
            'Sibu': { lat: 2.2876, lng: 111.8303, weather: 'Cerah, 24°C' },
            'Kuala Lumpur': { lat: 3.1390, lng: 101.6869, weather: 'Cerah, 28°C' },
            'Johor Bahru': { lat: 1.4927, lng: 103.7414, weather: 'Cerah, 26°C' },
            'Ipoh': { lat: 4.5979, lng: 101.0901, weather: 'Cerah, 25°C' },
            'Shah Alam': { lat: 3.0738, lng: 101.5183, weather: 'Cerah, 27°C' },
            'Melaka': { lat: 2.1896, lng: 102.2501, weather: 'Cerah, 26°C' },
            'Alor Setar': { lat: 6.1184, lng: 100.3688, weather: 'Cerah, 25°C' },
            'Miri': { lat: 4.3995, lng: 113.9910, weather: 'Cerah, 23°C' },
            'Kuching': { lat: 1.5497, lng: 110.3379, weather: 'Cerah, 24°C' },
            'Sandakan': { lat: 5.8394, lng: 118.1171, weather: 'Cerah, 22°C' }
        };
        
        // Get location value
        const location = locationInput.value.trim();
        
        // Find matching city
        let foundCity = null;
        for (const [cityName, coords] of Object.entries(cityCoordinates)) {
            if (location.toLowerCase().includes(cityName.toLowerCase())) {
                foundCity = coords;
                break;
            }
        }
        
        // If no exact match, try to find partial matches
        if (!foundCity) {
            for (const [cityName, coords] of Object.entries(cityCoordinates)) {
                if (cityName.toLowerCase().includes(location.toLowerCase()) || 
                    location.toLowerCase().includes(cityName.toLowerCase())) {
                    foundCity = coords;
                    break;
                }
            }
        }
        
        // Update coordinates and weather if city found
        if (foundCity) {
            latitudeInput.value = foundCity.lat.toFixed(7);
            longitudeInput.value = foundCity.lng.toFixed(7);
            
            // Update current weather field if it exists
            if (currentWeatherInput) {
                currentWeatherInput.value = foundCity.weather;
            }
            
            // Show success message
            showNotification('Location updated successfully!', 'success');
        } else {
            // Show warning if location not found
            showNotification('Location not found. Please enter a valid Malaysian city.', 'warning');
        }
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-2 rounded-xs text-xs text-white z-50 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'warning' ? 'bg-yellow-500' : 
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Add event listener to location input
    document.addEventListener('DOMContentLoaded', function() {
        const locationInput = document.getElementById('weather-location');
        if (locationInput) {
            locationInput.addEventListener('blur', updateLocationData);
        }
    });
    
    // Toggle email edit mode
    function toggleEmailEdit() {
        const editBtn = document.getElementById('email-edit-btn');
        const saveBtn = document.getElementById('email-save-btn');
        const isEditing = editBtn.textContent.includes('Batal');
        
        // All email fields can be edited
        const emailFields = [
            'email-smtp-host',
            'email-smtp-port',
            'email-username',
            'email-password',
            'email-encryption',
            'email-authentication',
            'email-from-name',
            'email-reply-to',
            'email-timeout',
            'email-max-retries'
        ];
        
        if (!isEditing) {
            // Enable edit mode
            emailFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = false;
                    element.classList.remove('bg-gray-100');
                    element.classList.add('bg-white', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
                }
            });
            
            // Change button text
            editBtn.innerHTML = '<span class="material-icons text-xs mr-2">close</span>Batal';
            editBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            editBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
            
            // Show save button
            saveBtn.classList.remove('hidden');
            
        } else {
            // Disable edit mode
            emailFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = true;
                    element.classList.remove('bg-white', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
                    element.classList.add('bg-gray-100');
                }
            });
            
            // Change button text back
            editBtn.innerHTML = '<span class="material-icons text-xs mr-2">edit</span>Edit Konfigurasi';
            editBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            editBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            
            // Hide save button
            saveBtn.classList.add('hidden');
        }
    }
    
    // Save email configuration
    function saveEmailConfig() {
        // Show loading state
        const saveBtn = event.target;
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<span class="material-icons text-xs mr-2">hourglass_empty</span>Menyimpan...';
        saveBtn.disabled = true;
        
        // Collect form data
        const formData = {
            smtp_host: document.getElementById('email-smtp-host').value,
            smtp_port: document.getElementById('email-smtp-port').value,
            username: document.getElementById('email-username').value,
            password: document.getElementById('email-password').value || '{{ $emailConfig->password }}',
            encryption: document.getElementById('email-encryption').value,
            authentication: document.getElementById('email-authentication').value,
            from_name: document.getElementById('email-from-name').value,
            reply_to: document.getElementById('email-reply-to').value,
            connection_timeout: document.getElementById('email-timeout').value,
            max_retries: document.getElementById('email-max-retries').value
        };
        
        // Make AJAX request
        fetch('{{ route("email-configurations.update", 1) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                ...formData,
                _method: 'PUT'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                // Exit edit mode
                toggleEmailEdit();
            } else {
                showNotification(data.message || 'Ralat semasa menyimpan', 'error');
            }
        })
        .catch(error => {
            showNotification('Ralat semasa menyimpan: ' + error.message, 'error');
        })
        .finally(() => {
            // Restore button state
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    }
    
    // Show test email modal
    function showTestEmailModal() {
        document.getElementById('testEmailModal').classList.remove('hidden');
        document.getElementById('recipientEmail').focus();
    }
    
    // Hide test email modal
    function hideTestEmailModal() {
        document.getElementById('testEmailModal').classList.add('hidden');
        document.getElementById('recipientEmail').value = '';
    }
    
    // Test email configuration
    function testEmailConfig() {
        const recipientEmail = document.getElementById('recipientEmail').value.trim();
        
        if (!recipientEmail) {
            showNotification('Sila masukkan email penerima', 'warning');
            document.getElementById('recipientEmail').focus();
            return;
        }
        
        if (!isValidEmail(recipientEmail)) {
            showNotification('Format email tidak sah', 'warning');
            document.getElementById('recipientEmail').focus();
            return;
        }
        
        // Show loading state
        const testBtn = event.target;
        const originalText = testBtn.innerHTML;
        testBtn.innerHTML = '<span class="material-icons text-sm mr-2">hourglass_empty</span>Testing...';
        testBtn.disabled = true;
        
        // Make AJAX request
        fetch('{{ route("email-configurations.test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                recipient_email: recipientEmail
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                hideTestEmailModal();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Ralat semasa menguji email: ' + error.message, 'error');
        })
        .finally(() => {
            // Restore button state
            testBtn.innerHTML = originalText;
            testBtn.disabled = false;
            
            // Refresh page to show updated test results
            setTimeout(() => {
                location.reload();
            }, 2000);
        });
    }

    // SMTP Health
    function smtpHealth() {
        const btn = event.target.closest('button');
        const original = btn.innerHTML;
        btn.innerHTML = '<span class="material-icons text-xs mr-2">hourglass_empty</span>Menguji...';
        btn.disabled = true;

        fetch('{{ route("email-configurations.smtp-health") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message + (data.latency_ms ? ' ('+data.latency_ms+' ms)' : ''), 'success');
            } else {
                showNotification(data.message || 'Gagal menguji SMTP', 'error');
            }
        })
        .catch(err => showNotification('Ralat SMTP health: ' + err.message, 'error'))
        .finally(() => { btn.innerHTML = original; btn.disabled = false; });
    }
    
    // Validate email format
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
</script>
</body>
</html>
