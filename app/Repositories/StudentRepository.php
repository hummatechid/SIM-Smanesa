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

//        try {
//            $response = file_get_contents($url, false, stream_context_create([
//                'http' => [
//                    'method' => 'GET',
//                    'header' => [
//                        'Authorization: ' . $authorizationToken,
//                    ],
//                ],
//            ]));
//
//// Cek jika request berhasil
//            if ($response === false) {
//                die('Error occurred while fetching data');
//            }
//
//            try{
//                // Membagi respons menjadi header dan body
//                list($header, $body) = explode("\r\n\r\n", $response, 2);
//
//            // Cetak header untuk referensi (jika diperlukan)
//            //            echo "Header:\n$header\n\n";
//
//                // Ubah body JSON menjadi objek PHP
//                $responseObject = json_decode($body);
//
//                // Periksa jika dekoding JSON berhasil
//                if (json_last_error() === JSON_ERROR_NONE) {
//                    // Lakukan sesuatu dengan objek
//                    dd($responseObject);
//                } else {
//                    // Tangani kesalahan JSON, jika ada
//                    echo "Gagal mendekode JSON. Kesalahan: " . json_last_error_msg();
//                }
//            }catch (\Exception $e){
////                dd(json_decode($response)->rows);
//                return json_decode($response);
//            }
//
//
//
//            // Make a GET request with the authorization header
////            $response = Http::withHeaders([
////                'Authorization' => $authorizationToken,
////            ])->get($url);
////
////            // Periksa status code
////            if ($response->status() == 200) {
////                dd($response);
////                // Lakukan sesuatu jika status code adalah 200
////                // Misalnya, mengambil data dari response
////                $data = $response->json();
////                // Lakukan sesuatu dengan $data
////            } else {
////                // Lempar pengecualian jika status code tidak 200
////                throw new \Exception('HTTP request failed with status code: ' . $response->status());
////            }
////
////            return $response->json();
//        }catch (\Exception $e) {
//            dd($e->getMessage());
//            return $e->getMessage();
//        }

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
}
