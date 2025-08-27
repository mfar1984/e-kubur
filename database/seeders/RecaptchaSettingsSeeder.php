<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tetapan;
use Illuminate\Support\Facades\Auth;

class RecaptchaSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 1; // Default admin user ID

        // reCAPTCHA Site Key
        Tetapan::updateOrCreate(
            ['kunci' => 'recaptcha_site_key'],
            [
                'nama' => 'reCAPTCHA Site Key',
                'nilai' => '6Lxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', // Placeholder - replace with actual key
                'jenis' => 'text',
                'penerangan' => 'Google reCAPTCHA v2 Site Key untuk feedback form',
                'boleh_edit' => true,
                'kategori' => 'Sistem',
                'susunan' => 50,
                'created_by' => $userId,
                'updated_by' => $userId
            ]
        );

        // reCAPTCHA Secret Key
        Tetapan::updateOrCreate(
            ['kunci' => 'recaptcha_secret_key'],
            [
                'nama' => 'reCAPTCHA Secret Key',
                'nilai' => '6Lxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', // Placeholder - replace with actual key
                'kunci' => 'recaptcha_secret_key',
                'jenis' => 'text',
                'penerangan' => 'Google reCAPTCHA v2 Secret Key untuk server-side verification',
                'boleh_edit' => true,
                'kategori' => 'Sistem',
                'susunan' => 51,
                'created_by' => $userId,
                'updated_by' => $userId
            ]
        );

        // reCAPTCHA Enabled
        Tetapan::updateOrCreate(
            ['kunci' => 'recaptcha_enabled'],
            [
                'nama' => 'Aktifkan reCAPTCHA',
                'nilai' => 'false',
                'jenis' => 'boolean',
                'penerangan' => 'Aktifkan atau nyahaktifkan reCAPTCHA untuk feedback form',
                'boleh_edit' => true,
                'kategori' => 'Sistem',
                'susunan' => 52,
                'created_by' => $userId,
                'updated_by' => $userId
            ]
        );

        $this->command->info('reCAPTCHA settings seeded successfully!');
    }
}
