<?php

namespace App\Services\MasterData;

use App\Repositories\TeacherRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;
use Illuminate\Support\Facades\Http;

class TeacherService extends BaseService {

    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->repository = $teacherRepository;
        $this->pageTitle = "Guru";
        $this->mainUrl = "teacher";
        $this->mainMenu = "teacher";
    }

    /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getDataDatatable() :JsonResponse
    {
        $data = $this->repository->getAll();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('full_name', function($item) {
                return $item->full_name;
            })->addColumn('email', function($item) {
                return $item->user->email;
            })->addColumn('phone_number', function($item) {
                return $item->phone_number;
            })->addColumn('action', function($item) {
                return 
                '<div class="d-flex gap-2 justify-content-start align-items-center">
                    <a href="'.route('teacher.show',$item->id).'" class="btn btn-sm btn-primary">Detail</a>
                    <button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Hapus</button>
                </div>';
            })->rawColumns(['action'])
            ->make(true);
    }

    /**
     * fetch all teacher from dapodik API
     * @return mixed
     */
    public function fetchTeacherFromDapodik(String $npsn, String $key): mixed
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
            return $data;
        } else {
            // Handle the error
            echo 'Error: ' . $response->status();
            return $response->status();
        }
    }

     /**
     * handle sync student from dapodik
     *
     * @param array $students
     * @return void
     */
    public function handleSyncTeacher(array $teachers): void
    {
        foreach ($teachers as $teacher) {
            $this->repository->updateOrCreateTeacher($teacher);
        }
    }
}