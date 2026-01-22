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
            ],
            [
                'name' => 'Tes User',
                'email' => 'demo_user@gmail.com',
                'nik' => '123456789',
                'jabatan' => 'Tenaga Harian Lepas',
                'bidang' => 'Bidang Pemeliharaan & Pembangunan',
                'uptd_id' => 1,
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Tes Mandor',
                'email' => 'demo_mandor@gmail.com',
                'nik' => '123456789',
                'jabatan' => 'Mandor',
                'bidang' => 'Bidang Pemeliharaan & Pembangunan',
                'role' => 'mandor',
                'uptd_id' => 1,
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Tes Pengamat',
                'email' => 'demo_pengamat@gmail.com',
                'nik' => '123456789',
                'jabatan' => 'Pengamat',
                'bidang' => 'Bidang Pemeliharaan & Pembangunan',
                'role' => 'pengamat',
                'uptd_id' => 1,
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Tes subkoor',
                'email' => 'demo_subkoor@gmail.com',
                'nik' => '123456789',
                'jabatan' => 'Pengamat',
                'bidang' => 'Bidang Pemeliharaan & Pembangunan',
                'role' => 'pengamat',
                'uptd_id' => 1,
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Tes KSPPJ',
                'email' => 'demo_ksppj@gmail.com',
                'nik' => '123456789',
                'jabatan' => 'KSPPJ',
                'bidang' => 'Bidang Pemeliharaan & Pembangunan',
                'role' => 'ksppj',
                'uptd_id' => 1,
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($data as $user) {
            ModelsUser::create($user);
        }
    }
}
