<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FAQController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $pageTitle = 'Soalan Lazim (FAQ) - E-Kubur';
        
        $faqs = [
            [
                'category' => 'Umum',
                'icon' => 'help',
                'color' => 'blue',
                'questions' => [
                    [
                        'question' => 'Apakah itu Sistem E-Kubur?',
                        'answer' => 'Sistem E-Kubur adalah platform pengurusan jenazah yang komprehensif untuk membantu pengurusan data kematian, ahli PPJUB, dan pentadbiran kubur secara digital.'
                    ],
                    [
                        'question' => 'Siapakah yang boleh menggunakan sistem ini?',
                        'answer' => 'Sistem ini direka untuk pengurus kubur, ahli PPJUB, dan kakitangan pentadbiran yang terlibat dalam pengurusan jenazah dan kubur.'
                    ],
                    [
                        'question' => 'Adakah sistem ini selamat untuk data sensitif?',
                        'answer' => 'Ya, sistem ini menggunakan teknologi keselamatan terkini dengan enkripsi data dan pengurusan akses yang ketat untuk melindungi maklumat sensitif.'
                    ]
                ]
            ],
            [
                'category' => 'Web Awam',
                'icon' => 'public',
                'color' => 'teal',
                'questions' => [
                    [
                        'question' => 'Di mana hendak membuat carian maklumat simati?',
                        'answer' => 'Pergi ke laman web awam (port 8080). Carian disediakan pada halaman utama dengan latar belakang bergambar. Masukkan nama atau IC; hasil akan dipaparkan dengan format kemas, termasuk lokasi dengan pautan Google Maps.'
                    ],
                    [
                        'question' => 'Bagaimana menghantar maklum balas di web awam?',
                        'answer' => 'Buka /feedback (tanpa .php melalui .htaccess). Isi nama, e‑mel, mesej dan lampiran (PDF/JPG/PNG sehingga 15MB setiap fail). Setelah dihantar, pentadbir menerima emel dan pengguna menerima emel pengesahan.'
                    ],
                    [
                        'question' => 'Mengapa ada masa solat yang memaparkan “--:--”?',
                        'answer' => 'Widget menggunakan data e‑Solat JAKIM. Jika sumber memberikan 00:00:00 atau tiada data untuk zon/hari tertentu, sistem memaparkan “--:--”. Pilihan zon boleh diubah dalam Tetapan Umum.'
                    ]
                ]
            ],
            [
                'category' => 'Pengurusan Kematian',
                'icon' => 'person',
                'color' => 'green',
                'questions' => [
                    [
                        'question' => 'Bagaimana untuk menambah rekod kematian baru?',
                        'answer' => 'Pergi ke menu "Daftar Kematian" dan klik butang "Tambah Kematian". Isi semua maklumat yang diperlukan termasuk info orang meninggal dan waris.'
                    ],
                    [
                        'question' => 'Bolehkah saya edit maklumat kematian yang telah direkodkan?',
                        'answer' => 'Ya, anda boleh edit maklumat kematian dengan mengklik butang "Edit" pada rekod yang berkenaan. Semua perubahan akan direkodkan dalam log audit.'
                    ],
                    [
                        'question' => 'Bagaimana untuk mencari rekod kematian tertentu?',
                        'answer' => 'Gunakan fungsi carian pada halaman "Daftar Kematian" dengan memasukkan nama, IC, atau maklumat lain yang berkaitan.'
                    ]
                ]
            ],
            [
                'category' => 'Ahli PPJUB',
                'icon' => 'group',
                'color' => 'purple',
                'questions' => [
                    [
                        'question' => 'Apakah itu PPJUB?',
                        'answer' => 'PPJUB adalah Persatuan Pengurusan Jenazah dan Kubur yang bertanggungjawab menguruskan jenazah dan kubur di kawasan tertentu.'
                    ],
                    [
                        'question' => 'Bagaimana untuk mendaftar ahli PPJUB baru?',
                        'answer' => 'Pergi ke menu "Ahli PPJUB" dan klik "Tambah Ahli". Isi semua maklumat yang diperlukan termasuk nama, IC, telefon, dan alamat.'
                    ],
                    [
                        'question' => 'Bolehkah saya mengemaskini status keahlian?',
                        'answer' => 'Ya, anda boleh mengemaskini status keahlian dari "Aktif" kepada "Tidak Aktif" atau sebaliknya melalui fungsi edit.'
                    ]
                ]
            ],
            [
                'category' => 'Pentadbiran Sistem',
                'icon' => 'settings',
                'color' => 'orange',
                'questions' => [
                    [
                        'question' => 'Bagaimana untuk menambah pengguna baru?',
                        'answer' => 'Pergi ke menu "Pengguna Akses" dan klik "Tambah Pengguna". Berikan kumpulan akses yang sesuai untuk pengguna tersebut.'
                    ],
                    [
                        'question' => 'Apakah perbezaan antara kumpulan akses?',
                        'answer' => 'Setiap kumpulan akses mempunyai izin yang berbeza. Admin mempunyai akses penuh, manakala pengguna biasa mempunyai akses terhad.'
                    ],
                    [
                        'question' => 'Bagaimana untuk mengkonfigurasi email sistem?',
                        'answer' => 'Pergi ke menu "Integrasi" dan pilih tab "Email (SMTP)". Masukkan maklumat SMTP yang diperlukan dan uji konfigurasi.'
                    ]
                ]
            ],
            [
                'category' => 'Integrasi & API',
                'icon' => 'api',
                'color' => 'indigo',
                'questions' => [
                    [
                        'question' => 'Bagaimana sistem berhubung dengan web awam untuk borang maklum balas?',
                        'answer' => 'Web awam menghantar data ke endpoint CMS (POST /api/v1/feedback) menggunakan Laravel Sanctum (Bearer Token). Token dihasilkan di Integrasi → API dan abilities disyorkan: write:feedback atau admin:all.'
                    ],
                    [
                        'question' => 'Bagaimana menguji kesihatan API?',
                        'answer' => 'Gunakan GET /health (web) atau GET /api/v1/health (api). Pada halaman Integrasi terdapat butang “Test API”.'
                    ],
                    [
                        'question' => 'Bagaimana menukar zon waktu solat yang digunakan widget?',
                        'answer' => 'Pergi ke Pentadbiran Sistem → Tetapan Umum dan pilih “Zon Waktu Solat (e‑Solat JAKIM)”. Sistem memanggil GET /api/esolat/today berdasarkan zon ini.'
                    ]
                ]
            ],
            [
                'category' => 'Legal',
                'icon' => 'gavel',
                'color' => 'amber',
                'questions' => [
                    [
                        'question' => 'Di mana saya boleh lihat Penafian, Privasi, dan Terma Penggunaan?',
                        'answer' => 'Di footer sistem, klik pautan Penafian, Privasi, atau Terma Penggunaan untuk membuka modal kandungan penuh. Peta Laman juga tersedia dalam modal yang sama.'
                    ],
                    [
                        'question' => 'Apakah dasar pemprosesan data peribadi?',
                        'answer' => 'Data diproses untuk tujuan operasi sah PPJUB. Akses terhad mengikut peranan. Emel pengesahan/ notifikasi dihantar mengikut keperluan. Rujuk Dasar Privasi untuk butiran penuh.'
                    ]
                ]
            ],
            [
                'category' => 'Teknikal',
                'icon' => 'build',
                'color' => 'red',
                'questions' => [
                    [
                        'question' => 'Bagaimana untuk menyemak status sistem?',
                        'answer' => 'Pergi ke menu "Status Sistem" untuk melihat status database, cache, storage, dan komponen sistem lain.'
                    ],
                    [
                        'question' => 'Apakah yang perlu dilakukan jika sistem tidak berfungsi?',
                        'answer' => 'Semak status sistem terlebih dahulu. Jika masih bermasalah, hubungi sokongan teknikal dengan menyediakan maklumat error yang muncul.'
                    ],
                    [
                        'question' => 'Bagaimana untuk mengemaskini sistem?',
                        'answer' => 'Kemaskini sistem perlu dilakukan oleh pentadbir sistem. Pastikan backup data dibuat sebelum sebarang kemaskini.'
                    ]
                ]
            ]
        ];

        return view('faq.index', compact('user', 'pageTitle', 'faqs'));
    }
}
