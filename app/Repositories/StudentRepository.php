<?php

namespace App\Repositories;

use App\Models\Student;
use Illuminate\Support\Facades\Http;

class StudentRepository extends BaseRepository
{

    public function __construct(Student $student)
    {
        $this->model = $student;
    }

    /**
     * override get all students
     *
     * @param bool|null $history
     * @return object
     */
    public function getAll(bool $history = null): object
    {
        return $this->model->query()->get();
    }

    /**
     * fetch all student from dapodik API
     * @return mixed
     */
    public function fetchStudentFromDapodik(String $npsn, String $key): mixed
    {
        // Specify the URL
        $url = 'http://dapo.smanesa.id:5774/WebService/getPesertaDidik?npsn=' . $npsn;

        // Set the authorization header
        $authorizationToken = 'Bearer ' . $key;


        $response = Http::withHeaders([
            'Authorization' => $authorizationToken,
        ])->timeout(1200)->get($url);

        // Check if the request was successful (status code 2xx)
        if ($response->successful()) {
            // Get the response body as an array or JSON object
            $data = $response->json();
            //            dd($data);
            return $data;
            // Output the data
            //            print_r($data);
        } else {
            // Handle the error
            echo 'Error: ' . $response->status();

            return  $response->status();
        }
    }

    /**
     * get student by nisn
     *
     * @param String $nisn
     * @return mixed
     */
    public function getStudentByNisn(String $nisn): mixed
    {
        return $this->model->where('nisn', $nisn)->first();
    }

    /**
     * update or create student to database
     * @param array $student
     */
    public function updateOrCreateStudent(array $student): void
    {
        $this->model->query()
            ->updateOrCreate(
                [
                    'nik' => $student['nik'],
                    'nisn' => $student['nisn'],
                ],
                [
                    'email' => $student['email'],
                    'nik' => $student['nik'],
                    'nisn' => $student['nisn'],
                    'nipd' => $student['nipd'],
                    'full_name' => $student['nama'],
                    'gender' => ($student['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan',
                    'phone_number' => $student['nomor_telepon_seluler'],
                    'home_phone_number' => $student['nomor_telepon_rumah'],
                    'address' => $student['alamat_jalan'],
                    'religion' => $student['agama_id_str'],
                    'nama_rombel' => $student['nama_rombel'],
                    'violation_score' => 0,
                    'register_type' => $student['jenis_pendaftaran_id_str'],
                    'school_origin' => $student['sekolah_asal'],
                    'birth_location' => $student['tempat_lahir'],
                    'birth_date' => $student['tanggal_lahir'],
                    'father_name' => $student['nama_ayah'],
                    'father_job' => $student['pekerjaan_ayah_id_str'],
                    'mother_name' => $student['nama_ibu'],
                    'mother_job' => $student['pekerjaan_ibu_id_str'],
                    'guardian_name' => $student['nama_wali'],
                    'guardian_job' => $student['pekerjaan_wali_id_str'],
                    'anak_keberapa' => $student['anak_keberapa'],
                    'height' => $student['tinggi_badan'],
                    'weight' => $student['berat_badan'],
                    'semester' => $student['semester_id'],
                    'tingkat_pendidikan' => $student['tingkat_pendidikan_id'],
                    'kurikulum' => $student['kurikulum_id_str'],
                    'kebutuhan_khusus' => $student['kebutuhan_khusus'],
                ]
            );
    }
}
