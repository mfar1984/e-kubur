<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserGuideController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $pageTitle = 'Panduan Pengguna - E-Kubur';
        
        $guides = [
            [
                'title' => 'Waktu Solat (e‑Solat JAKIM)',
                'icon' => 'schedule',
                'color' => 'amber',
                'sections' => [
                    [
                        'subtitle' => 'Mengaktifkan Widget Waktu Solat',
                        'steps' => [
                            'Pergi ke Pentadbiran Sistem → Tetapan Umum',
                            'Di medan “Zon Waktu Solat (e‑Solat JAKIM)”, pilih zon yang dikehendaki',
                            'Klik “Simpan Tetapan”',
                            'Refresh halaman; widget waktu solat akan muncul di topbar (desktop)'
                        ]
                    ],
                    [
                        'subtitle' => 'Sumber Data & Endpoint',
                        'steps' => [
                            'Sumber rasmi: e‑Solat JAKIM',
                            'Endpoint dalaman: GET /api/esolat/today — mengambil zon daripada tetapan',
                            'Parameter debug: tambah ?debug=1 untuk melihat JSON mentah',
                            'Format masa dipaparkan sebagai hh:mm AM/PM; nilai kosong/00:00:00 akan dipaparkan “--:--”'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Integrasi Web Awam & API',
                'icon' => 'api',
                'color' => 'teal',
                'sections' => [
                    [
                        'subtitle' => 'Guna Borang Maklum Balas (Web Awam)',
                        'steps' => [
                            'Buka laman web awam di http://localhost:8080/feedback',
                            'Isi Nama, E-mel, dan Mesej',
                            'Lampirkan fail PDF/JPEG/PNG jika perlu (≤ 15MB setiap fail)',
                            'Klik Hantar — sistem akan hantar emel kepada pentadbir dan emel pengesahan kepada anda (1–3 hari bekerja)'
                        ]
                    ],
                    [
                        'subtitle' => 'Keselamatan & Token Sanctum',
                        'steps' => [
                            'Token dihasilkan melalui Integrasi → API → Generate Token',
                            'Token format: <id>|<token>; di DB hanya disimpan hash SHA-256',
                            'Abilities minimum untuk feedback: write:feedback atau admin:all',
                            'Contoh: Authorization: Bearer 13|XXXXXXXXXXXXXXXXXXXX'
                        ]
                    ],
                    [
                        'subtitle' => 'Endpoint API Berkaitan',
                        'steps' => [
                            'Kesihatan: GET /health (web) dan GET /api/v1/health (api)',
                            'Maklum balas: POST /api/v1/feedback (auth:sanctum)',
                            'Ujian emel: POST /api/v1/email/test (auth:sanctum)'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Memulakan Sistem',
                'icon' => 'play_circle',
                'color' => 'blue',
                'sections' => [
                    [
                        'subtitle' => 'Log Masuk ke Sistem',
                        'steps' => [
                            'Buka pelayar web dan pergi ke alamat sistem E-Kubur',
                            'Masukkan nama pengguna dan kata laluan anda',
                            'Klik butang "Log Masuk" untuk mengakses sistem',
                            'Pastikan anda mempunyai akses yang sah untuk menggunakan sistem'
                        ]
                    ],
                    [
                        'subtitle' => 'Navigasi Utama',
                        'steps' => [
                            'Menu utama terletak di bahagian atas halaman',
                            'Gunakan menu "Pengurusan" untuk akses Daftar Kematian dan Ahli PPJUB',
                            'Menu "Pentadbiran Sistem" untuk tetapan, pengguna, dan log audit',
                            'Widget cuaca menunjukkan maklumat cuaca semasa di bahagian kanan'
                        ]
                    ],
                    [
                        'subtitle' => 'Aplikasi Pantas',
                        'steps' => [
                            'Klik ikon grid (apps) di bahagian atas untuk akses pantas',
                            'Pilih aplikasi yang ingin anda akses dari senarai',
                            'Ini membolehkan navigasi pantas tanpa melalui menu utama'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Pengurusan Kematian',
                'icon' => 'person',
                'color' => 'red',
                'sections' => [
                    [
                        'subtitle' => 'Menambah Rekod Kematian Baru',
                        'steps' => [
                            'Pergi ke menu "Pengurusan" → "Daftar Kematian"',
                            'Klik butang "Tambah Kematian" di bahagian atas halaman',
                            'Isi maklumat "Info Orang Meninggal": Nama, Tarikh Lahir, Kad Pengenalan, Tarikh Meninggal',
                            'Isi maklumat lokasi: Longitude dan Latitude (gunakan peta untuk memilih lokasi)',
                            'Isi maklumat "Info Waris": Nama Waris dan Telefon HP',
                            'Klik "Simpan" untuk menyimpan rekod kematian'
                        ]
                    ],
                    [
                        'subtitle' => 'Mengemaskini Rekod Kematian',
                        'steps' => [
                            'Cari rekod kematian yang ingin dikemaskini menggunakan fungsi carian',
                            'Klik butang "Edit" pada rekod yang berkenaan',
                            'Ubah maklumat yang diperlukan',
                            'Klik "Kemaskini" untuk menyimpan perubahan'
                        ]
                    ],
                    [
                        'subtitle' => 'Mencari Rekod Kematian',
                        'steps' => [
                            'Gunakan kotak carian di bahagian atas halaman Daftar Kematian',
                            'Masukkan nama, IC, atau maklumat lain yang berkaitan',
                            'Klik butang "Cari" atau tekan Enter',
                            'Keputusan carian akan dipaparkan dalam jadual'
                        ]
                    ],
                    [
                        'subtitle' => 'Melihat Butiran Kematian',
                        'steps' => [
                            'Klik butang "Lihat" pada rekod kematian yang ingin dilihat',
                            'Halaman butiran akan memaparkan semua maklumat lengkap',
                            'Lokasi akan dipaparkan dalam peta interaktif',
                            'Klik koordinat lokasi untuk membuka Google Maps'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Pengurusan Ahli PPJUB',
                'icon' => 'group',
                'color' => 'purple',
                'sections' => [
                    [
                        'subtitle' => 'Mendaftar Ahli PPJUB Baru',
                        'steps' => [
                            'Pergi ke menu "Pengurusan" → "Ahli PPJUB"',
                            'Klik butang "Tambah Ahli" di bahagian atas halaman',
                            'Isi maklumat ahli: Nama, IC, Telefon, Alamat, dan maklumat lain yang diperlukan',
                            'Pilih status keahlian: Aktif atau Tidak Aktif',
                            'Klik "Simpan Ahli" untuk menyimpan rekod'
                        ]
                    ],
                    [
                        'subtitle' => 'Mengemaskini Maklumat Ahli',
                        'steps' => [
                            'Cari ahli yang ingin dikemaskini menggunakan fungsi carian',
                            'Klik butang "Edit" pada rekod ahli yang berkenaan',
                            'Ubah maklumat yang diperlukan',
                            'Klik "Kemaskini Ahli" untuk menyimpan perubahan'
                        ]
                    ],
                    [
                        'subtitle' => 'Menguruskan Status Keahlian',
                        'steps' => [
                            'Buka rekod ahli yang ingin diubah statusnya',
                            'Klik butang "Edit"',
                            'Ubah status dari "Aktif" kepada "Tidak Aktif" atau sebaliknya',
                            'Simpan perubahan untuk mengemaskini status keahlian'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Pentadbiran Sistem',
                'icon' => 'admin_panel_settings',
                'color' => 'orange',
                'sections' => [
                    [
                        'subtitle' => 'Tetapan Umum',
                        'steps' => [
                            'Pergi ke menu "Pentadbiran Sistem" → "Tetapan Umum"',
                            'Lihat dan kemaskini tetapan sistem seperti nama organisasi, alamat, dan maklumat lain',
                            'Klik "Simpan" untuk menyimpan perubahan tetapan',
                            'Tetapan ini akan digunakan dalam sistem secara automatik'
                        ]
                    ],
                    [
                        'subtitle' => 'Pengurusan Kumpulan Akses',
                        'steps' => [
                            'Pergi ke menu "Pentadbiran Sistem" → "Kumpulan Akses"',
                            'Klik "Tambah Kumpulan" untuk membuat kumpulan akses baru',
                            'Berikan nama kumpulan dan pilih izin yang diperlukan',
                            'Klik "Simpan" untuk membuat kumpulan akses'
                        ]
                    ],
                    [
                        'subtitle' => 'Pengurusan Pengguna Akses',
                        'steps' => [
                            'Pergi ke menu "Pentadbiran Sistem" → "Pengguna Akses"',
                            'Klik "Tambah Pengguna" untuk mendaftar pengguna baru',
                            'Isi maklumat pengguna: Nama, Emel, Telefon, dan kata laluan',
                            'Pilih kumpulan akses yang sesuai untuk pengguna tersebut',
                            'Klik "Simpan" untuk mendaftar pengguna baru'
                        ]
                    ],
                    [
                        'subtitle' => 'Log Audit & Keselamatan',
                        'steps' => [
                            'Pergi ke menu "Pentadbiran Sistem" → "Log Audit & Keselamatan"',
                            'Lihat rekod aktiviti sistem dan pengguna',
                            'Gunakan penapis untuk mencari aktiviti tertentu',
                            'Eksport log untuk tujuan audit dan keselamatan'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Integrasi Sistem',
                'icon' => 'integration_instructions',
                'color' => 'green',
                'sections' => [
                    [
                        'subtitle' => 'Konfigurasi Email SMTP',
                        'steps' => [
                            'Pergi ke menu "Pentadbiran Sistem" → "Integrasi"',
                            'Pilih tab "Email (SMTP)"',
                            'Isi maklumat SMTP: Host, Port, Username, Password, dan Encryption',
                            'Klik "Test Email" untuk menguji konfigurasi',
                            'Klik "Simpan Perubahan" untuk menyimpan konfigurasi'
                        ]
                    ],
                    [
                        'subtitle' => 'Konfigurasi Cuaca',
                        'steps' => [
                            'Pilih tab "Cuaca" dalam halaman Integrasi',
                            'Isi maklumat API: Provider, API Key, Base URL, dan tetapan lain',
                            'Tetapkan lokasi default dan bahasa yang dikehendaki',
                            'Klik "Test Weather" untuk menguji API cuaca',
                            'Klik "Simpan Perubahan" untuk menyimpan konfigurasi'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Fungsi Lanjutan',
                'icon' => 'build',
                'color' => 'indigo',
                'sections' => [
                    [
                        'subtitle' => 'Status Sistem',
                        'steps' => [
                            'Pergi ke menu "Bantuan & Sokongan" → "Status Sistem"',
                            'Lihat status keseluruhan sistem dan komponennya',
                            'Semak status database, cache, storage, dan API',
                            'Klik "Kemas Kini" untuk menyegarkan status sistem'
                        ]
                    ],
                    [
                        'subtitle' => 'Eksport Data',
                        'steps' => [
                            'Dalam halaman senarai (Kematian, PPJUB, dll), klik butang "Eksport"',
                            'Pilih format eksport yang dikehendaki (Excel, PDF)',
                            'Sistem akan menjana fail eksport dengan data semasa',
                            'Muat turun fail untuk tujuan backup atau analisis'
                        ]
                    ],
                    [
                        'subtitle' => 'Carian dan Penapis',
                        'steps' => [
                            'Gunakan kotak carian untuk mencari rekod tertentu',
                            'Gunakan penapis untuk menyempitkan keputusan carian',
                            'Kombinasikan carian dan penapis untuk keputusan yang lebih tepat',
                            'Klik "Reset" untuk mengosongkan semua penapis'
                        ]
                    ]
                ]
            ]
        ];

        return view('user-guide.index', compact('user', 'pageTitle', 'guides'));
    }
}
