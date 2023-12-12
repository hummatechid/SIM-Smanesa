<?php

namespace App\Services;

use App\Repositories\StudentRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;
use stdClass;

class HomeService extends BaseService {
    private $teacherRepository, $studentRepository;

    public function __construct(
        TeacherRepository $teacherRepository,
        StudentRepository $studentRepository,
    )
    {
        $this->teacherRepository = $teacherRepository;
        $this->studentRepository = $studentRepository;
        $this->pageTitle = "Dashboard";
        $this->mainUrl = "/";
        $this->mainMenu = "dashboard";
    }

    /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getDataDatatable() :JsonResponse
    {
        $data = [];

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
                    <a href="'.route('user.show',$item->id).'" class="btn btn-sm btn-primary">Detail</a>
                    <button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Hapus</button>
                </div>';
            })->rawColumns(['action'])
            ->make(true);
    }

    public function countCard()
    {
        // mak object data
        $data = new stdClass;

        // count teacher
        $data->teacher = $this->teacherRepository->randomData("count");
        // count student
        $data->student = $this->studentRepository->randomData("count");

        return $data;
    }
}