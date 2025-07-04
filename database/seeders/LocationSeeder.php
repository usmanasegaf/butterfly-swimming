<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location; // Pastikan Anda mengimpor model Location
use Illuminate\Support\Facades\DB; // Digunakan jika ingin menggunakan DB::table()

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang sudah ada di tabel locations (opsional, jika ingin selalu bersih)
        // Location::truncate(); // Atau DB::table('locations')->truncate();

        // Data lokasi pertama
        Location::create([
            'name'      => 'Kolam Renang Mekarwangi', // Berikan nama yang deskriptif
            'address'   => 'Jl. Taman Mekar Abadi I No.36a, Mekarwangi, Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40237',
            'latitude'  => -6.954972877110996,
            'longitude' => 107.60699357229478,
        ]);

        // Anda bisa menambahkan lokasi lain di sini jika ada
        // Location::create([
        //     'name'      => 'Lokasi Lain',
        //     'address'   => 'Alamat lokasi lain',
        //     'latitude'  => -6.xxxx,
        //     'longitude' => 107.xxxx,
        // ]);
    }
}