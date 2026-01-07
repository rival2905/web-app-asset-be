<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterLokasiKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(base_path('database/data/master_ruas_jalan.json'));
        $dataJson = json_decode($json, true);

        // Validasi jika JSON tidak valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON file is invalid: ' . json_last_error_msg());
        }

        // Data awal dari array
        $data = [
            "Kota Bandung",
            "Kota Banjar",
            "Kota Tasikmalaya",
            "Kota Cimahi",
            "Kota Depok",
            "Kota Bekasi",
            "Kota Cirebon",
            "Kota Sukabumi",
            "Kota Bogor",
            "Kab. Pangandaran",
            "Kab. Bandung Barat",
            "Kab. Bekasi",
            "Kab. Karawang",
            "Kab. Purwakarta",
            "Kab. Subang",
            "Kab. Indramayu",
            "Kab. Sumedang",
            "Kab. Majalengka",
            "Kab. Cirebon",
            "Kab. Kuningan",
            "Kab. Ciamis",
            "Kab. Tasikmalaya",
            "Kab. Garut",
            "Kab. Bandung",
            "Kab. Cianjur",
            "Kab. Sukabumi",
            "Kab. Bogor",
        ];

        // Gabungkan array manual dengan JSON array
        $data = array_merge($data, $dataJson);

        // Iterasi setiap data untuk proses penyimpanan
        foreach ($data as $value) {
            // Jika data berupa string, simpan langsung
            if (is_string($value)) {
                // Cek jika data sudah ada untuk menghindari duplikasi
                if (!\App\Models\MasterLokasiKerja::where('nama', $value)->exists()) {
                    \App\Models\MasterLokasiKerja::create(['nama' => $value]);
                }
            }
            // Jika data berupa array
            else if (is_array($value) && array_key_exists('nama_ruas_jalan', $value)) {
                // Gabungkan nama_ruas_jalan dengan id_ruas_jalan jika nama_ruas_jalan sudah ada
                $namaRuas = $value['nama_ruas_jalan'];
                if (\App\Models\MasterLokasiKerja::where('nama', $namaRuas)->exists()) {
                    $namaRuas .= " - " . $value['id_ruas_jalan'];
                }

                // Simpan data
                \App\Models\MasterLokasiKerja::create([
                    'nama' => $namaRuas,
                    'ruas_jalan_id' => $value['id_ruas_jalan'] ?? null,
                    'uptd_id' => $value['uptd_id'] ?? null,
                ]);
            }
        }
    }
}
