<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReleaseNotesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $pageTitle = 'Nota Keluaran - E-Kubur';
        
        $releases = [
            [
                'version' => 'v2.5.0',
                'date' => now()->format('Y-m-d'),
                'type' => 'major',
                'title' => 'Sistem Maklum Balas Awam dengan reCAPTCHA & Pengesahan Emel',
                'description' => 'Sistem maklum balas awam yang lengkap dengan reCAPTCHA v2 Invisible, pengesahan emel 6-digit, dan sistem lampiran fail yang canggih.',
                'highlights' => [
                    '🔒 reCAPTCHA v2 Invisible untuk perlindungan bot',
                    '✉️ Sistem pengesahan emel 6-digit dengan expiry 15 minit',
                    '📎 Lampiran fail (JPEG, PNG, PDF) sehingga 100MB',
                    '📱 Responsive design untuk mobile dan desktop',
                    '🔐 Pengesahan dua langkah: reCAPTCHA + Kod Emel',
                    '📧 Notifikasi automatik kepada pentadbir dan pengguna'
                ],
                'features' => [
                    [
                        'category' => 'Sistem Maklum Balas',
                        'items' => [
                            'Borang maklum balas awam dengan reCAPTCHA v2 Invisible',
                            'Pengesahan emel 6-digit sebelum hantar maklum balas',
                            'Sokongan lampiran fail (JPEG, PNG, PDF) sehingga 100MB',
                            'Sistem session management untuk pengesahan cross-port',
                            'Notifikasi automatik kepada pentadbir dan pengguna'
                        ]
                    ],
                    [
                        'category' => 'Keselamatan & Anti-Bot',
                        'items' => [
                            'Google reCAPTCHA v2 Invisible dengan konfigurasi dari Tetapan',
                            'Pengesahan emel 6-digit dengan expiry 15 minit',
                            'Validasi fail dengan type checking dan size limits',
                            'IP address dan user agent logging untuk audit'
                        ]
                    ],
                    [
                        'category' => 'UI/UX & Responsive',
                        'items' => [
                            'Design responsive untuk mobile dan desktop',
                            'Modal pengesahan kod yang mobile-friendly',
                            'Button "Sahkan" dengan layout yang optimum',
                            'Separator line untuk visual hierarchy yang jelas'
                        ]
                    ],
                ],
                'technical' => [
                    'API Endpoints: POST /api/v1/feedback, POST /api/v1/feedback/verify',
                    'reCAPTCHA Config: Tetapan Umum dengan site key dan secret key',
                    'File Storage: storage/app/public/feedback-attachments/',
                    'Session Management: Database driver dengan session restoration',
                    'Frontend: public_website/feedback.php dengan recaptcha-helper.js'
                ]
            ],
            [
                'version' => 'v2.4.0',
                'date' => now()->format('Y-m-d'),
                'type' => 'minor',
                'title' => 'Integrasi e‑Solat JAKIM untuk Waktu Solat',
                'description' => 'Widget waktu solat di topbar dengan pilihan zon dari Tetapan dan endpoint API dalaman.',
                'highlights' => [
                    '🕌 Widget waktu solat (Imsak, Subuh, Syuruk, Dhuha, Zohor, Asar, Maghrib, Isyak)',
                    '🌐 Pilihan zon e‑Solat di Tetapan (dropdown penuh mengikut negeri)',
                    '⚙️ Endpoint dalaman GET /api/esolat/today (ambil zon dari tetapan)',
                    '🧠 Normalisasi kunci API (dhuhr/zuhr, isha/isyak, dll) + format AM/PM',
                    '📄 Penambahan halaman legal: Penafian, Privasi, Terma Penggunaan, Peta Laman (modal footer)'
                ],
                'features' => [
                    [
                        'category' => 'Waktu Solat',
                        'items' => [
                            'Topbar menayangkan waktu semasa ikut zon',
                            'Fallback “--:--” jika sumber JAKIM beri 00:00:00/tiada data',
                            'Parameter debug ?debug=1 untuk semakan JSON mentah',
                            'Font weight 400 secara lalai; 15 minit sebelum masuk waktu: bold + blinking',
                        ]
                    ],
                    [
                        'category' => 'Legal & Footer',
                        'items' => [
                            'Modal footer dengan empat halaman legal (BM): Penafian, Dasar Privasi, Terma Penggunaan, Peta Laman',
                            'Kandungan disusun dan boleh diskrol; tipografi konsisten; tajuk jelas',
                            'Peta Laman memaparkan struktur menu sistem (Papan Pemuka, Pengurusan, Pentadbiran Sistem, Bantuan & Rujukan)'
                        ]
                    ],
                    [
                        'category' => 'UI/UX',
                        'items' => [
                            'Seragamkan saiz butang Edit/Padam pada halaman PPJUB agar setara dengan Kematian',
                            'Tingkatkan keterlihatan input Kod Keselamatan pada semua popup padam (teks gelap, placeholder kelabu)',
                            'Kemas kini FAQ & User Guide untuk integrasi e‑Solat dan legal',
                        ]
                    ],
                ],
                'technical' => [
                    'Route API: routes/api.php → /api/esolat/today',
                    'Tetapan zon: resources/views/tetapan/index.blade.php (prayer_zone)',
                    'Paparan: resources/views/components/double-navbar.blade.php',
                    'Footer & Legal: resources/views/components/footer.blade.php'
                ]
            ],
            [
                'version' => 'v2.3.0',
                'date' => now()->format('Y-m-d'),
                'type' => 'minor',
                'title' => 'Integrasi API Web Awam & Pengesahan Sanctum',
                'description' => 'Sambungan rasmi antara CMS E-Kubur dan Web Awam, termasuk borang maklum balas, emel pengesahan kepada pengguna, dan notifikasi pentadbir.',
                'highlights' => [
                    '🔗 Sambungan API CMS ↔ Web Awam (Sanctum)',
                    '✉️ Emel notifikasi ke pentadbir (email_configurations.username)',
                    '📬 Emel pengesahan kepada pengirim maklum balas',
                    '🧪 Endpoint kesihatan /health dan /api/v1/health',
                    '🔐 Token peribadi Sanctum dengan abilities'
                ],
                'features' => [
                    [
                        'category' => 'Integrasi API',
                        'items' => [
                            'Endpoint baharu: POST /api/v1/feedback (auth:sanctum; abilities: write:feedback atau admin:all)',
                            'Borang maklum balas publik di public_website/feedback.php (port 8080)',
                            'Lampiran disokong (PDF, JPEG, PNG) sehingga 15MB per fail',
                            'Simpan lampiran ke storage bersama (storage/app/public/feedback) dan attach ke emel pentadbir'
                        ]
                    ],
                    [
                        'category' => 'Emel & Notifikasi',
                        'items' => [
                            'Hantar emel ke pentadbir berdasarkan email_configurations.username',
                            'Hantar emel pengesahan kepada pengguna (SLA: 1–3 hari bekerja)',
                            'Penambahan ujian SMTP manual dan health check untuk diagnostik'
                        ]
                    ],
                    [
                        'category' => 'Keselamatan',
                        'items' => [
                            'Gunakan Laravel Sanctum (Bearer <id>|<token>)',
                            'Token abilities disemak (write:feedback | admin:all)',
                            'Jangan simpan token plaintext di DB (hash SHA-256 disimpan)'
                        ]
                    ]
                ],
                'technical' => [
                    'Route API: routes/api.php → /api/v1/feedback',
                    'Controller: app/Http/Controllers/FeedbackController.php',
                    'Web Awam: public_website/feedback.php',
                    'Sanctum: HasApiTokens pada User, endpoints token (web)',
                    'SMTP: EmailConfiguration sebagai sumber alamat pentadbir'
                ]
            ],
            [
                'version' => 'v2.2.0',
                'date' => '2024-12-25',
                'type' => 'major',
                'title' => 'Peningkatan Profil Pengguna & Pembetulan Sistem',
                'description' => 'Kemaskini utama dengan sistem profil pengguna yang lengkap, pembetulan database, dan peningkatan UI/UX.',
                'highlights' => [
                    '👤 Sistem Profil Pengguna Lengkap',
                    '🔧 Pembetulan Database & Migration',
                    '📱 Peningkatan UI/UX Mobile',
                    '🎨 Standardisasi Font & Icon'
                ],
                'features' => [
                    [
                        'category' => 'Fitur Baru',
                        'items' => [
                            'Halaman profil pengguna dengan maklumat lengkap',
                            'Sistem edit profil dengan validation lengkap',
                            'Update kata laluan dengan verification',
                            'Halaman tetapan pengguna peribadi',
                            'Sistem FAQ interaktif dengan carian',
                            'Panduan pengguna dengan navigasi pantas',
                            'Nota keluaran dengan filter versi',
                            'Status sistem dengan monitoring real-time'
                        ]
                    ],
                    [
                        'category' => 'Peningkatan',
                        'items' => [
                            'Peningkatan responsif mobile untuk semua halaman utama',
                            'Standardisasi font sizes dan weights di seluruh aplikasi',
                            'Peningkatan alignment icon dalam navigation menu',
                            'Pembetulan spacing dan layout untuk mobile view',
                            'Peningkatan form validation dan error handling',
                            'Pembetulan responsive design untuk peta interaktif'
                        ]
                    ],
                    [
                        'category' => 'Pembetulan',
                        'items' => [
                            'Pembetulan migration database PPJUB table',
                            'Pembetulan icon sizing dalam navigation dan topbar',
                            'Pembetulan font consistency di seluruh aplikasi',
                            'Pembetulan mobile layout untuk kematian, PPJUB, roles',
                            'Pembetulan user access dan audit logs mobile view',
                            'Pembetulan integrations page mobile responsiveness',
                            'Pembetulan system status page mobile layout'
                        ]
                    ]
                ],
                'technical' => [
                    'Laravel 12.21.0 dengan PHP 8.4.10',
                    'Alpine.js 3.x untuk interaktiviti frontend',
                    'Tailwind CSS untuk styling responsive',
                    'Material Icons untuk iconography',
                    'OpenStreetMap untuk peta interaktif',
                    'Tomorrow.io Weather API untuk data cuaca',
                    'Spatie Permission untuk roles dan permissions',
                    'Spatie Activity Log untuk audit logging'
                ]
            ],
            [
                'version' => 'v2.1.0',
                'date' => '2024-01-15',
                'type' => 'major',
                'title' => 'Peningkatan Utama & Integrasi Cuaca',
                'description' => 'Kemaskini utama dengan penambahan integrasi cuaca, peningkatan UI/UX, dan pembetulan bug kritikal.',
                'highlights' => [
                    '🌤️ Integrasi API Cuaca Real-time',
                    '📱 Peningkatan Responsif Mobile',
                    '🎨 UI/UX yang Lebih Moden',
                    '🔒 Keselamatan yang Dipertingkatkan'
                ],
                'features' => [
                    [
                        'category' => 'Fitur Baru',
                        'items' => [
                            'Integrasi API cuaca Tomorrow.io untuk maklumat cuaca real-time',
                            'Widget cuaca dalam navigation bar',
                            'Sistem status kesihatan komprehensif',
                            'Halaman FAQ interaktif dengan fungsi carian',
                            'Panduan pengguna lengkap dengan navigasi pantas',
                            'Nota keluaran terperinci untuk setiap versi'
                        ]
                    ],
                    [
                        'category' => 'Peningkatan',
                        'items' => [
                            'Peningkatan responsif mobile untuk semua halaman',
                            'Optimasi peta interaktif untuk mobile dan desktop',
                            'Peningkatan sistem carian dan penapis',
                            'UI yang lebih konsisten di seluruh aplikasi',
                            'Peningkatan kelajuan loading halaman',
                            'Pembetulan alignment dan spacing elemen UI'
                        ]
                    ],
                    [
                        'category' => 'Pembetulan',
                        'items' => [
                            'Pembetulan bug dalam konfigurasi email SMTP',
                            'Pembetulan masalah redirect selepas simpan konfigurasi',
                            'Pembetulan display koordinat lokasi dalam peta',
                            'Pembetulan responsive design pada beberapa halaman',
                            'Pembetulan icon sizing dalam navigation menu',
                            'Pembetulan modal test email configuration'
                        ]
                    ]
                ],
                'technical' => [
                    'Laravel 12.21.0 dengan PHP 8.4.10',
                    'Alpine.js 3.x untuk interaktiviti frontend',
                    'Tailwind CSS untuk styling responsive',
                    'Material Icons untuk iconography',
                    'OpenStreetMap untuk peta interaktif',
                    'Tomorrow.io Weather API untuk data cuaca'
                ]
            ],
            [
                'version' => 'v2.0.0',
                'date' => '2024-01-01',
                'type' => 'major',
                'title' => 'Pelancaran Sistem E-Kubur',
                'description' => 'Pelancaran rasmi Sistem E-Kubur dengan semua modul utama dan fungsi pentadbiran.',
                'highlights' => [
                    '🏗️ Sistem Pengurusan Jenazah Lengkap',
                    '👥 Pengurusan Ahli PPJUB',
                    '⚙️ Pentadbiran Sistem Komprehensif',
                    '📊 Log Audit & Keselamatan'
                ],
                'features' => [
                    [
                        'category' => 'Modul Utama',
                        'items' => [
                            'Sistem pengurusan kematian dengan peta interaktif',
                            'Pengurusan ahli PPJUB dengan status keahlian',
                            'Pentadbiran sistem dengan tetapan umum',
                            'Pengurusan kumpulan akses dan izin',
                            'Pengurusan pengguna akses sistem',
                            'Sistem log audit dan keselamatan'
                        ]
                    ],
                    [
                        'category' => 'Fitur Pentadbiran',
                        'items' => [
                            'Dashboard dengan statistik sistem',
                            'Sistem tetapan konfigurasi umum',
                            'Pengurusan integrasi email SMTP',
                            'Sistem eksport data (Excel, PDF)',
                            'Fungsi carian dan penapis lanjutan',
                            'Sistem backup dan pemulihan data'
                        ]
                    ],
                    [
                        'category' => 'Keselamatan',
                        'items' => [
                            'Sistem autentikasi yang selamat',
                            'Pengurusan izin berdasarkan kumpulan',
                            'Log audit untuk semua aktiviti sistem',
                            'Enkripsi data sensitif',
                            'Sistem backup automatik',
                            'Monitoring aktiviti mencurigakan'
                        ]
                    ]
                ],
                'technical' => [
                    'Laravel 12.x dengan PHP 8.4',
                    'MySQL database dengan enkripsi',
                    'Tailwind CSS untuk UI responsive',
                    'Alpine.js untuk interaktiviti',
                    'Material Design Icons',
                    'OpenStreetMap integration'
                ]
            ],
            [
                'version' => 'v1.5.0',
                'date' => '2023-12-15',
                'type' => 'minor',
                'title' => 'Peningkatan UI/UX & Mobile',
                'description' => 'Kemaskini fokus pada peningkatan pengalaman pengguna dan optimasi mobile.',
                'highlights' => [
                    '📱 Optimasi Mobile Experience',
                    '🎨 Peningkatan UI Design',
                    '⚡ Peningkatan Performance',
                    '🔧 Pembetulan Bug'
                ],
                'features' => [
                    [
                        'category' => 'Mobile Optimization',
                        'items' => [
                            'Responsive design untuk semua halaman',
                            'Touch-friendly interface untuk mobile',
                            'Optimasi loading untuk peranti mobile',
                            'Peningkatan navigasi mobile',
                            'Card-based layout untuk mobile view',
                            'Mobile-optimized forms dan buttons'
                        ]
                    ],
                    [
                        'category' => 'UI Improvements',
                        'items' => [
                            'Peningkatan konsistensi design system',
                            'Pembetulan spacing dan alignment',
                            'Peningkatan readability text',
                            'Optimasi color scheme',
                            'Peningkatan icon consistency',
                            'Better visual hierarchy'
                        ]
                    ],
                    [
                        'category' => 'Performance',
                        'items' => [
                            'Optimasi database queries',
                            'Peningkatan loading speed',
                            'Lazy loading untuk images',
                            'Caching improvements',
                            'Reduced bundle size',
                            'Better error handling'
                        ]
                    ]
                ],
                'technical' => [
                    'Tailwind CSS responsive utilities',
                    'Alpine.js performance optimizations',
                    'Laravel query optimization',
                    'Mobile-first CSS approach',
                    'Progressive Web App features',
                    'Service worker implementation'
                ]
            ],
            [
                'version' => 'v1.0.0',
                'date' => '2023-12-01',
                'type' => 'initial',
                'title' => 'Versi Beta - Sistem E-Kubur',
                'description' => 'Pelancaran beta Sistem E-Kubur dengan fungsi asas untuk pengujian dan feedback.',
                'highlights' => [
                    '🚀 Pelancaran Beta System',
                    '📝 Fungsi Asas Lengkap',
                    '🧪 Testing & Feedback',
                    '📋 Documentation Awal'
                ],
                'features' => [
                    [
                        'category' => 'Core Features',
                        'items' => [
                            'Sistem autentikasi pengguna',
                            'Pengurusan data kematian asas',
                            'Pengurusan ahli PPJUB',
                            'Dashboard statistik ringkas',
                            'Sistem tetapan asas',
                            'Log aktiviti sistem'
                        ]
                    ],
                    [
                        'category' => 'Database',
                        'items' => [
                            'Struktur database lengkap',
                            'Migrations untuk semua modul',
                            'Seeders untuk data contoh',
                            'Relationships antar modul',
                            'Indexing untuk performance',
                            'Backup system asas'
                        ]
                    ],
                    [
                        'category' => 'Security',
                        'items' => [
                            'Sistem login/logout',
                            'Password hashing',
                            'CSRF protection',
                            'Input validation',
                            'SQL injection prevention',
                            'XSS protection'
                        ]
                    ]
                ],
                'technical' => [
                    'Laravel 12.x framework',
                    'PHP 8.4 compatibility',
                    'MySQL database',
                    'Bootstrap CSS framework',
                    'jQuery untuk JavaScript',
                    'Basic authentication system'
                ]
            ]
        ];

        return view('release-notes.index', compact('user', 'pageTitle', 'releases'));
    }
}
