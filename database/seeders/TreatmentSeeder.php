<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Treatment;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                "category" => "Pelanggaran ringan",
                "min_score" => 10,
                "max_score" => 30,
                "action" => "Peringatan ke I (Petugas Ketertiban)"
            ], [
                "category" => "Pelanggaran ringan",
                "min_score" => 31,
                "max_score" => 50,
                "action" => "Peringatan ke II (Guru BK)"
            ], [
                "category" => "Pelanggaran sedang",
                "min_score" => 51,
                "max_score" => 75,
                "action" => "Panggilan Orang Tua ke I (Guru BK)"
            ], [
                "category" => "Pelanggaran sedang",
                "min_score" => 76,
                "max_score" => 100,
                "action" => "Panggilan Orang Tua ke II (Guru BK)"
            ], [
                "category" => "Pelanggaran berat",
                "min_score" => 101,
                "max_score" => 175,
                "action" => "Home visit (Wali kelas dan Guru BK)"
            ], [
                "category" => "Pelanggaran berat",
                "min_score" => 176,
                "max_score" => 249,
                "action" => "Skorsing (Petugas Ketertiban)"
            ], [
                "category" => "Pelanggaran berat",
                "min_score" => 250,
                "max_score" => 999,
                "action" => "Dikembalikan ke Orang Tua (Kepala Sekolah)"
            ],
        ];

        foreach($datas as $data) {
            Treatment::create($data);
        }
    }
}
