<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterUnit;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'name' => 'UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan I',
                'slug' => 'uptd-pengelolaan-jalan-dan-jembatan-wilayah-pelayanan-i',
                'uptd_id' => '1'
            ],
            [
                'name' => 'UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan II',
                'slug' => 'uptd-pengelolaan-jalan-dan-jembatan-wilayah-pelayanan-ii',
                'uptd_id' => '2'
            ],
            [
                'name' => 'UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan III',
                'slug' => 'uptd-pengelolaan-jalan-dan-jembatan-wilayah-pelayanan-iii',
                'uptd_id' => '3'
            ],
            [
                'name' => 'UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan IV',
                'slug' => 'uptd-pengelolaan-jalan-dan-jembatan-wilayah-pelayanan-iv',
                'uptd_id' => '4'
            ],
            [
                'name' => 'UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan V',
                'slug' => 'uptd-pengelolaan-jalan-dan-jembatan-wilayah-pelayanan-v',
                'uptd_id' => '5'
            ],
            [
                'name' => 'UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan VI',
                'slug' => 'uptd-pengelolaan-jalan-dan-jembatan-wilayah-pelayanan-vi',
                'uptd_id' => '6'
            ],
            [
                'name' => 'Sekretariat',
                'slug' => 'sekretariat'
            ],
            [
                'name' => 'Bidang Pemeliharaan dan Pembangunan Jalan',
                'slug' => 'bidang-pemeliharaan-dan-pembangunan-jalan'
            ],
            [
                'name' => 'Bidang Jasa Konstruksi',
                'slug' => 'bidang-jasa-konstruksi'
            ],
            [
                'name' => 'Bidang Penataan Ruang',
                'slug' => 'bidang-penataan-ruang'
            ],
            [
                'name' => 'Bidang Teknik Jalan',
                'slug' => 'bidang-teknik-jalan'
            ]
        ];
        foreach ($data as $unit) {
            MasterUnit::create($unit);
        }
    }
}
