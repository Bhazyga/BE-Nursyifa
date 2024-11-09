<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $materials = [];

        for ($i = 0; $i < 10; $i++) {
            $materials[] = [
                'nama' => $faker->word,
                'deskripsi' => $faker->paragraph,
                'kategori' => $faker->word,
                'stok' => $faker->numberBetween(1, 10),
                'harga' => $faker->randomFloat(2, 5000, 50000),
                'gambar' => 'sample.jpg',
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime
            ];
        }

        DB::table('materials')->insert($materials);
    }
}
