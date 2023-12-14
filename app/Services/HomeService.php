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

     /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getDataDatatableStudentMustViolation() :JsonResponse
    {
        $data = [];

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($item) {
                return $item->full_name;
            })->addColumn('kelas', function($item) {
                return $item->user->email;
            })->addColumn('poin', function($item) {
                return $item->phone_number;
            })->addColumn('total', function($item) {
                return $item->phone_number;
            })->rawColumns([])
            ->make(true);
    }

     /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getDataDatatableStudentMustLate() :JsonResponse
    {
        $data = [];

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($item) {
                return $item->full_name;
            })->addColumn('kelas', function($item) {
                return $item->user->email;
            })->addColumn('total', function($item) {
                return $item->phone_number;
            })->rawColumns([])
            ->make(true);
    }

     /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getDataDatatableStudentNewAttendance() :JsonResponse
    {
        $data = [];

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($item) {
                return $item->full_name;
            })->addColumn('kelas', function($item) {
                return $item->user->email;
            })->addColumn('date', function($item) {
                return $item->phone_number;
            })->rawColumns([])
            ->make(true);
    }
}