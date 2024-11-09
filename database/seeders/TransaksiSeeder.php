<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaksis')->insert([
            [
                'namapenyewa' => 'John Doe',
                'alamatpenyewa' => '123 Main St, Cityville',
                'notelp' => '123-456-7890',
                'bis' => 'City Transport',
                'harga' => 150000,
                'berapalama' => Carbon::now()->addHours(3), // Example of a 3-hour duration
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'namapenyewa' => 'Jane Smith',
                'alamatpenyewa' => '456 Elm St, Townsville',
                'notelp' => '987-654-3210',
                'bis' => 'Mountain Tours',
                'harga' => 200000,
                'berapalama' => Carbon::now()->addHours(5), // Example of a 5-hour duration
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'namapenyewa' => 'Alice Johnson',
                'alamatpenyewa' => '789 Oak St, Metropolis',
                'notelp' => '555-123-4567',
                'bis' => 'Luxury Travels',
                'harga' => 300000,
                'berapalama' => Carbon::now()->addHours(8), // Example of an 8-hour duration
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
