<?php

namespace App\Services\MasterData;

use App\Repositories\TeacherRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;
use Illuminate\Support\Facades\Http;

class TeacherService extends BaseService
{

    private UserRepository $userRepository;

    public function __construct(TeacherRepository $teacherRepository, UserRepository $userRepository)
    {
        $this->repository = $teacherRepository;
        $this->pageTitle = "GTK";
        $this->mainUrl = "teacher";
        $this->mainMenu = "teacher";
        $this->breadCrumbs = ["GTK" => route('teacher.index')];
        $this->userRepository = $userRepository;
    }

    /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getDataDatatable(array|object $data): JsonResponse
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('full_name', function ($item) {
                $fullname = '<div>';
                if($item->is_dapodik) $fullname .= '<span class="text-primary" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Tersinkronisasi dengan dapodik" data-bs-placement="top"><i class="bi bi-patch-check-fill"></i></span> ';
                $fullname .= $item->full_name;
                $fullname .= '</div>';
                return $fullname;
            })->addColumn('email', function ($item) {
                return $item->user->email;
            })->addColumn('phone_number', function ($item) {
                return $item->phone_number;
            })->addColumn('action', function ($item) {
                $button = '<div class="d-flex gap-2 justify-content-start align-items-center">
                    <a href="' . route('teacher.show', $item->id) . '" class="btn btn-sm btn-primary">Detail</a>';
                if(auth()->user()->hasRole('superadmin') && !$item->is_dapodik) {
                    $button .= '<button class="btn btn-sm btn-danger delete-data" data-id="' . $item->id . '">Hapus</button>';
                }
                $button .= '</div>';

                return $button;
            })->rawColumns(['action', 'full_name'])
            ->make(true);
    }

    /**
     * fetch all teacher from dapodik API
     * @return mixed
     */
    public function fetchTeacherFromDapodik(String $npsn, String $key): mixed
    {
        // Specify the URL
        $url = 'http://dapo.smanesa.id:5774/WebService/getGtk?npsn=' . $npsn;

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
            $check = $this->userRepository->getTeacherByNik($teacher['nik']);

            if ($check == null) {
                $newUser = $this->userRepository->createTeacher($teacher);
                $this->repository->createTeacher($teacher, $newUser->id);
            } else {
                $this->repository->updateTeacher($teacher, $check);
            }
        }
    }
}
