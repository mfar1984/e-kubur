<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tetapan;
use App\Models\User;

class TetapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::first();
        
        $tetapan = [
            // Umum
            [
                'kunci' => 'nama_sistem',
                'nama' => 'Nama Sistem',
                'nilai' => 'E-Kubur',
                'jenis' => 'text',
                'penerangan' => 'Nama rasmi sistem pengurusan jenazah',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 1,
            ],
            [
                'kunci' => 'versi_sistem',
                'nama' => 'Versi Sistem',
                'nilai' => '1.0.0',
                'jenis' => 'text',
                'penerangan' => 'Versi semasa sistem',
                'boleh_edit' => false,
                'kategori' => 'umum',
                'susunan' => 2,
            ],
            [
                'kunci' => 'alamat_sistem',
                'nama' => 'Alamat Sistem',
                'nilai' => 'Jalan Masjid, 93000 Kuching, Sarawak',
                'jenis' => 'text',
                'penerangan' => 'Alamat rasmi sistem',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 3,
            ],
            [
                'kunci' => 'default_latitude',
                'nama' => 'Latitude Default',
                'nilai' => '2.3000',
                'jenis' => 'number',
                'penerangan' => 'Latitude default untuk maps (Kuching: 2.3000)',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 4,
            ],
            [
                'kunci' => 'default_longitude',
                'nama' => 'Longitude Default',
                'nilai' => '111.8167',
                'jenis' => 'number',
                'penerangan' => 'Longitude default untuk maps (Kuching: 111.8167)',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 5,
            ],
            [
                'kunci' => 'prayer_zone',
                'nama' => 'Zon Waktu Solat (e-Solat JAKIM)',
                'nilai' => 'SWK16',
                'jenis' => 'text',
                'penerangan' => 'Kod zon JAKIM untuk waktu solat (contoh SWK16 Bintulu)',
                'boleh_edit' => true,
                'kategori' => 'umum',
                'susunan' => 6,
            ],
            
            // Sistem
            [
                'kunci' => 'maintenance_mode',
                'nama' => 'Mode Penyelenggaraan',
                'nilai' => false,
                'jenis' => 'boolean',
                'penerangan' => 'Aktifkan mode penyelenggaraan sistem',
                'boleh_edit' => true,
                'kategori' => 'sistem',
                'susunan' => 1,
            ],
            [
                'kunci' => 'max_login_attempts',
                'nama' => 'Maksimum Percubaan Login',
                'nilai' => '5',
                'jenis' => 'number',
                'penerangan' => 'Bilangan maksimum percubaan login sebelum account dikunci',
                'boleh_edit' => true,
                'kategori' => 'sistem',
                'susunan' => 2,
            ],
            [
                'kunci' => 'session_timeout',
                'nama' => 'Masa Tamat Sesi',
                'nilai' => '120',
                'jenis' => 'number',
                'penerangan' => 'Masa dalam minit sebelum sesi tamat',
                'boleh_edit' => true,
                'kategori' => 'sistem',
                'susunan' => 3,
            ],
            
            // Emel
            [
                'kunci' => 'smtp_host',
                'nama' => 'SMTP Host',
                'nilai' => 'smtp.gmail.com',
                'jenis' => 'text',
                'penerangan' => 'Host server SMTP untuk emel',
                'boleh_edit' => true,
                'kategori' => 'emel',
                'susunan' => 1,
            ],
            [
                'kunci' => 'smtp_port',
                'nama' => 'SMTP Port',
                'nilai' => '587',
                'jenis' => 'number',
                'penerangan' => 'Port server SMTP',
                'boleh_edit' => true,
                'kategori' => 'emel',
                'susunan' => 2,
            ],
            [
                'kunci' => 'smtp_encryption',
                'nama' => 'SMTP Encryption',
                'nilai' => 'tls',
                'jenis' => 'text',
                'penerangan' => 'Jenis encryption untuk SMTP',
                'boleh_edit' => true,
                'kategori' => 'emel',
                'susunan' => 3,
            ],
            
            // Notifikasi
            [
                'kunci' => 'notify_new_user',
                'nama' => 'Notifikasi Pengguna Baru',
                'nilai' => true,
                'jenis' => 'boolean',
                'penerangan' => 'Hantar notifikasi apabila ada pengguna baru',
                'boleh_edit' => true,
                'kategori' => 'notifikasi',
                'susunan' => 1,
            ],
            [
                'kunci' => 'notify_login_failed',
                'nama' => 'Notifikasi Login Gagal',
                'nilai' => true,
                'jenis' => 'boolean',
                'penerangan' => 'Hantar notifikasi apabila ada percubaan login gagal',
                'boleh_edit' => true,
                'kategori' => 'notifikasi',
                'susunan' => 2,
            ],
            [
                'kunci' => 'notify_system_error',
                'nama' => 'Notifikasi Ralat Sistem',
                'nilai' => true,
                'jenis' => 'boolean',
                'penerangan' => 'Hantar notifikasi apabila ada ralat sistem',
                'boleh_edit' => true,
                'kategori' => 'notifikasi',
                'susunan' => 3,
            ],
        ];
        
        foreach ($tetapan as $item) {
            Tetapan::updateOrCreate(
                ['kunci' => $item['kunci']],
                array_merge($item, [
                    'created_by' => $adminUser ? $adminUser->id : null,
                    'updated_by' => $adminUser ? $adminUser->id : null,
                ])
            );
        }
    }
}
