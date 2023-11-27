<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nik' => "5278787771728",
                'nisn' => "8686832",
                'nipd' => "69672367",
                'full_name' => "Nana Rikanda",
                'gender' => "Perempuan",
                'phone_number' => "089872182923",
                'address' => "Jalan. Kiharja darisangta",
                'religion' => "Islam",
                'nama_rombel' => "XI RPL 1",
                'violation_score' => 0,
            ],
            [
                'nik' => "2576638638123",
                'nisn' => "09829383",
                'nipd' => "08371737",
                'full_name' => "Sri Rahmawait Priharja",
                'gender' => "Perempuan",
                'phone_number' => "082912349312",
                'address' => "Jalan. Kedetranpasi dalai salam",
                'religion' => "Islam",
                'nama_rombel' => "XI RPL 1",
                'violation_score' => 0,
            ],
            [
                'nik' => "36826783912792",
                'nisn' => "08277477",
                'nipd' => "86271622",
                'full_name' => "Nima Kiti Saji",
                'gender' => "Perempuan",
                'phone_number' => "082182129720",
                'address' => "Jalan. Permatasari darisaji raharjo",
                'religion' => "Islam",
                'nama_rombel' => "XI RPL 1",
                'violation_score' => 0,
            ],
            [
                'nik' => "5625720820001",
                'nisn' => "02883888",
                'nipd' => "72971772",
                'full_name' => "Zaki tarharjo rahardian",
                'gender' => "Laki-laki",
                'phone_number' => "089667126681",
                'address' => "Jalan. Kokarji tarasih mardake",
                'religion' => "Islam",
                'nama_rombel' => "XI RPL 1",
                'violation_score' => 0,
            ],
            [
                'nik' => "0621278189287",
                'nisn' => "08217127",
                'nipd' => "55261223",
                'full_name' => "Priharjo Darimawan",
                'gender' => "Laki-laki",
                'phone_number' => "08217830927",
                'address' => "Jalan. Kades maharjo dariyanti",
                'religion' => "Islam",
                'nama_rombel' => "XI RPL 1",
                'violation_score' => 0,
            ],
            [
                'nik' => "3019276621022",
                'nisn' => "07265716",
                'nipd' => "61287212",
                'full_name' => "Prizi Dermawan",
                'gender' => "Laki-laki",
                'phone_number' => "081289020211",
                'address' => "Jalan. Koasi Raharjos Blok C",
                'religion' => "Islam",
                'nama_rombel' => "XI RPL 1",
                'violation_score' => 0,
            ],
            [
                'nik' => "3051205001205",
                'nisn' => "06872123",
                'nipd' => "52416261",
                'full_name' => "Kodek Riki Srimawan",
                'gender' => "Laki-laki",
                'phone_number' => "08129773912",
                'address' => "Jalan. Parsaje Dermawan Troji",
                'religion' => "Islam",
                'nama_rombel' => "XI RPL 1",
                'violation_score' => 0,
            ],
        ];

        foreach($data as $student) Student::create($student);
    }
}
