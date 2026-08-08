<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name'                => 'Pembersihan Filter IPAL',
                'description'         => 'Membersihkan unit filter biologis dan fisik dari kotoran dan endapan yang menghambat kinerja sistem pengolahan.',
                'base_price'          => 350000,
                'duration_estimation' => 120,
                'is_active'           => true,
            ],
            [
                'name'                => 'Penggantian Media Biofilter',
                'description'         => 'Mengganti media biofilter (bio-ball, sarang tawon) yang sudah jenuh agar bakteri pengurai bekerja optimal kembali.',
                'base_price'          => 850000,
                'duration_estimation' => 240,
                'is_active'           => true,
            ],
            [
                'name'                => 'Desinfeksi Unit IPAL',
                'description'         => 'Proses desinfeksi menyeluruh menggunakan bahan kimia yang sesuai standar untuk mematikan patogen berbahaya.',
                'base_price'          => 450000,
                'duration_estimation' => 180,
                'is_active'           => true,
            ],
            [
                'name'                => 'Kalibrasi & Perbaikan Sensor pH',
                'description'         => 'Kalibrasi ulang sensor pH dan DO agar pembacaan akurat. Penggantian elektroda jika sudah tidak layak pakai.',
                'base_price'          => 300000,
                'duration_estimation' => 90,
                'is_active'           => true,
            ],
            [
                'name'                => 'Penggantian Pompa Aerasi',
                'description'         => 'Penggantian pompa aerasi atau blower yang rusak untuk memastikan suplai oksigen ke unit biofilter tetap optimal.',
                'base_price'          => 1200000,
                'duration_estimation' => 300,
                'is_active'           => true,
            ],
            [
                'name'                => 'Inspeksi Pipa & Valve',
                'description'         => 'Pemeriksaan menyeluruh kondisi pipa, fitting, dan valve. Perbaikan kebocoran minor termasuk dalam paket ini.',
                'base_price'          => 250000,
                'duration_estimation' => 60,
                'is_active'           => true,
            ],
            [
                'name'                => 'Pengurasan Lumpur (Sludge)',
                'description'         => 'Pengosongan dan pengangkutan lumpur yang terakumulasi di tangki sedimentasi agar tidak mengganggu kinerja sistem.',
                'base_price'          => 750000,
                'duration_estimation' => 360,
                'is_active'           => true,
            ],
            [
                'name'                => 'Uji Kualitas Efluen',
                'description'         => 'Pengambilan sampel dan pengujian parameter kualitas air hasil olahan: COD, BOD, TSS, dan pH sesuai baku mutu.',
                'base_price'          => 500000,
                'duration_estimation' => 120,
                'is_active'           => true,
            ],
            [
                'name'                => 'Konsultasi & Pemeriksaan Rutin',
                'description'         => 'Kunjungan rutin untuk memeriksa kondisi umum IPAL dan memberikan rekomendasi perawatan preventif.',
                'base_price'          => 200000,
                'duration_estimation' => 60,
                'is_active'           => true,
            ],
        ];
 
        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
