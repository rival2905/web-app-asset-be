<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User as ModelsUser;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'nik' => '123213',
                'jabatan' => 'Admin',
                'bidang' => 'Sekretariat',
                'password' => bcrypt('password'),
                'role' => 'admin-pusat',
                'unit_id' => 7,

            ],
            [
                'name' => 'Tes User',
                'email' => 'demo_user@gmail.com',
                'nik' => '123456789',
                'jabatan' => 'Pegawai',
                'bidang' => 'Bidang Pemeliharaan & Pembangunan',
                'password' => bcrypt('password'),
                'role' => 'pegawai',
                'unit_id' => 8,

            ],
            [
                'name' => 'Tes Penanggung Jawab',
                'email' => 'demo_pj@gmail.com',
                'nik' => '123456789',
                'jabatan' => 'Penanggung Jawab',
                'bidang' => 'Bidang Pemeliharaan & Pembangunan',
                'role' => 'penanggung-jawab',
                'unit_id' => 8,
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Tes Ka UPTD',
                'email' => 'demo_kauptd@gmail.com',
                'nik' => '123456789',
                'jabatan' => 'Kepala UPTD',
                'bidang' => 'UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan I',
                'role' => 'kuptd',
                'unit_id' => 1,
                'uptd_id' => 1,
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Tes subkoor',
                'email' => 'demo_subkoor@gmail.com',
                'nik' => '123456789',
                'jabatan' => 'Sub Koor',
                'bidang' => 'UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan I',
                'role' => 'subkoor',
                'unit_id' => 1,
                'uptd_id' => 1,
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Tes KSPPJ',
                'email' => 'demo_ksppj@gmail.com',
                'nik' => '123456789',
                'jabatan' => 'KSPPJ',
                'bidang' => 'UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan I',
                'role' => 'ksppj',
                'uptd_id' => 1,
                'unit_id' => 1,
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($data as $user) {
            ModelsUser::create($user);
        }
    }
}
