<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangkeluarSeeder extends Seeder
{
    public function run()
    {
        // Example seed data
        DB::table('barangkeluar')->insert([
            [
                'material_id' => 22,
                'quantity' => 50,
                'tanggal_keluar' => '2024-10-01',
                'keterangan' => 'Pengeluaran untuk proyek A',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
