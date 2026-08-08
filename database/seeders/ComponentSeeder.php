<?php

namespace Database\Seeders;

use App\Models\Component;
use Illuminate\Database\Seeder;

class ComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $components = [
            // ── Sparepart ────────────────────────────────────────────
            [
                'name'          => 'Pompa Aerasi 1/4 HP',
                'type'          => 'sparepart',
                'unit'          => 'pcs',
                'default_price' => 450000,
                'description'   => 'Pompa aerasi kapasitas kecil untuk IPAL rumah tangga.',
            ],
            [
                'name'          => 'Pompa Aerasi 1/2 HP',
                'type'          => 'sparepart',
                'unit'          => 'pcs',
                'default_price' => 750000,
                'description'   => 'Pompa aerasi kapasitas sedang untuk IPAL komersial.',
            ],
            [
                'name'          => 'Diffuser Membran',
                'type'          => 'sparepart',
                'unit'          => 'pcs',
                'default_price' => 85000,
                'description'   => 'Diffuser untuk mendistribusikan udara ke dalam air.',
            ],
            [
                'name'          => 'Selang Udara 8mm',
                'type'          => 'sparepart',
                'unit'          => 'meter',
                'default_price' => 12000,
                'description'   => 'Selang penghubung pompa ke diffuser.',
            ],
            [
                'name'          => 'Check Valve 1/2 inch',
                'type'          => 'sparepart',
                'unit'          => 'pcs',
                'default_price' => 45000,
                'description'   => 'Mencegah aliran balik di saluran pipa.',
            ],
            [
                'name'          => 'Ball Valve PVC 3/4 inch',
                'type'          => 'sparepart',
                'unit'          => 'pcs',
                'default_price' => 35000,
                'description'   => 'Valve pengatur aliran air.',
            ],
            [
                'name'          => 'Elektroda Sensor pH',
                'type'          => 'sparepart',
                'unit'          => 'pcs',
                'default_price' => 350000,
                'description'   => 'Elektroda pengganti untuk sensor pH yang aus.',
            ],
            [
                'name'          => 'Lampu UV Sterilisasi 11W',
                'type'          => 'sparepart',
                'unit'          => 'pcs',
                'default_price' => 285000,
                'description'   => 'Lampu UV untuk sterilisasi efluen akhir.',
            ],
            [
                'name'          => 'Float Switch',
                'type'          => 'sparepart',
                'unit'          => 'pcs',
                'default_price' => 65000,
                'description'   => 'Sensor ketinggian air otomatis.',
            ],
 
            // ── Material ─────────────────────────────────────────────
            [
                'name'          => 'Media Bio-Ball',
                'type'          => 'material',
                'unit'          => 'liter',
                'default_price' => 25000,
                'description'   => 'Media biofilter berbentuk bola untuk tempat tumbuh bakteri.',
            ],
            [
                'name'          => 'Media Sarang Tawon',
                'type'          => 'material',
                'unit'          => 'liter',
                'default_price' => 18000,
                'description'   => 'Media biofilter berbentuk honeycomb.',
            ],
            [
                'name'          => 'Kaporit (Kalsium Hipoklorit)',
                'type'          => 'material',
                'unit'          => 'kg',
                'default_price' => 22000,
                'description'   => 'Bahan desinfektan klorin.',
            ],
            [
                'name'          => 'Tawas (Alum)',
                'type'          => 'material',
                'unit'          => 'kg',
                'default_price' => 15000,
                'description'   => 'Koagulan untuk menjernihkan air.',
            ],
            [
                'name'          => 'Soda Api (NaOH)',
                'type'          => 'material',
                'unit'          => 'kg',
                'default_price' => 35000,
                'description'   => 'Pengatur pH untuk air yang terlalu asam.',
            ],
            [
                'name'          => 'Karbon Aktif Granular',
                'type'          => 'material',
                'unit'          => 'kg',
                'default_price' => 55000,
                'description'   => 'Menyerap bau dan warna pada air olahan.',
            ],
            [
                'name'          => 'Pasir Silika',
                'type'          => 'material',
                'unit'          => 'kg',
                'default_price' => 8000,
                'description'   => 'Media filter fisik untuk menyaring padatan tersuspensi.',
            ],
 
            // ── Tool / Alat ──────────────────────────────────────────
            [
                'name'          => 'Sewa Pompa Sedot Lumpur',
                'type'          => 'tool',
                'unit'          => 'hari',
                'default_price' => 350000,
                'description'   => 'Pompa untuk menyedot lumpur dari tangki sedimentasi.',
            ],
            [
                'name'          => 'Sewa Tangki Pengangkut',
                'type'          => 'tool',
                'unit'          => 'trip',
                'default_price' => 500000,
                'description'   => 'Tangki untuk mengangkut lumpur ke IPLT.',
            ],
            [
                'name'          => 'pH Meter Digital',
                'type'          => 'tool',
                'unit'          => 'hari',
                'default_price' => 100000,
                'description'   => 'Alat ukur pH air portabel.',
            ],
            [
                'name'          => 'DO Meter',
                'type'          => 'tool',
                'unit'          => 'hari',
                'default_price' => 150000,
                'description'   => 'Alat ukur kadar oksigen terlarut dalam air.',
            ],
 
            // ── Komponen Struktural ──────────────────────────────────
            [
                'name'          => 'Pipa PVC 2 inch',
                'type'          => 'component',
                'unit'          => 'meter',
                'default_price' => 28000,
                'description'   => 'Pipa PVC standar untuk distribusi air.',
            ],
            [
                'name'          => 'Pipa PVC 3 inch',
                'type'          => 'component',
                'unit'          => 'meter',
                'default_price' => 45000,
                'description'   => 'Pipa PVC untuk saluran utama.',
            ],
            [
                'name'          => 'Fitting Elbow 90° 2 inch',
                'type'          => 'component',
                'unit'          => 'pcs',
                'default_price' => 8000,
                'description'   => 'Sambungan belokan 90 derajat.',
            ],
            [
                'name'          => 'Lem PVC',
                'type'          => 'component',
                'unit'          => 'kaleng',
                'default_price' => 25000,
                'description'   => 'Perekat sambungan pipa PVC.',
            ],
        ];
 
        foreach ($components as $component) {
            Component::updateOrCreate(
                ['name' => $component['name']],
                [...$component, 'is_active' => true]
            );
        }
    }
}
