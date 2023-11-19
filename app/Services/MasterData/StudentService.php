<?php

namespace App\Services\MasterData;

use App\Repositories\StudentRepository;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Yajra\Datatables\Facades\Datatables;

class StudentService extends BaseService
{
    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
        $this->pageTitle = "Siswa";
        $this->mainUrl = "student";
        $this->mainMenu = "student";
    }

    /**
     * handle sync student from dapodik
     *
     * @param array $students
     * @return void
     */
    public function handleSyncStudent(array $students): void
    {
        foreach ($students as $student) {
            $check = $this->repository->getStudentByNisn($student['nisn']);
            if($check) {
                $this->repository->updateStudent($check, $student);
            } else {
                $this->repository->insertStudent($student);
            }
        }
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
            ->addColumn('name', function($item) {
                return $item->name;
            })->addColumn('nisn', function($item) {
                return $item->nisn;
            })->addColumn('nipd', function($item) {
                return $item->nipd;
            })->addColumn('gender', function($item) {
                return $item->gender;
            })->addColumn('nama_rombel', function($item) {
                return $item->nama_rombel;
            })->addColumn('action', function($item) {
                return '<button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Detail</button>';
            })->rawColumns(['action'])
            ->make(true);
    }
}
