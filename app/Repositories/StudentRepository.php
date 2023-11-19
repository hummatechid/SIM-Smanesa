<?php

namespace App\Repositories;

use App\Models\Student;
use Illuminate\Support\Facades\Http;

class StudentRepository {

    private Student $model;
    public function __construct(Student $student)
    {
        $this->model = $student;
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

        // Make a GET request with the authorization header
        $response = Http::withHeaders([
            'Authorization' => $authorizationToken,
        ])->get($url);

        // Check if the request was successful (status code 2xx)
        if ($response->successful()) {
            // Get the response body as an array or JSON object
            $data = $response->json();
            return $data;
            // Output the data
//            print_r($data);
        } else {
            // Handle the error
            echo 'Error: ' . $response->status();

            return null;
        }
    }

    /**
     * get all student from database
     * @return mixed
     */
    public function getAllStudent(): mixed
    {
        return $this->model->all();
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
     * insert student to database
     * @param array $student
     */
    public function insertStudent(array $student): void
    {
        $this->model->create([
            'nik' => $student['nik'],
            'nisn' => $student['nisn'],
            'nipd' => $student['nipd'],
            'full_name' => $student['nama'],
            'gender' => ($student['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan',
            'phone_number' => $student['nomor_telepon_seluler'],
            'address' => $student['alamat_jalan'],
            'religion' => $student['agama_id_str'],
            'nama_rombel' => $student['nama_rombel'],
            'violation_score' => 0,
        ]);
    }

    /**
     * update student to database
     *
     * @param Student $student
     * @param array $newStudent
     * @return void
     */
    public function updateStudent(Student $student, array $newStudent): void
    {
        $student->update([
            'nik' => $newStudent['nik'],
            'nisn' => $newStudent['nisn'],
            'nipd' => $newStudent['nipd'],
            'full_name' => $newStudent['nama'],
            'gender' => ($newStudent['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan',
            'phone_number' => $newStudent['nomor_telepon_seluler'],
            'address' => $newStudent['alamat_jalan'],
            'religion' => $newStudent['agama_id_str'],
            'nama_rombel' => $newStudent['nama_rombel']
        ]);
    }
}
