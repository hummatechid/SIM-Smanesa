<?php

namespace App\Services\MasterData;

use App\Repositories\StudentRepository;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class StudentService extends BaseService
{
    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
        $this->pageTitle = "Peserta Didik";
        $this->mainUrl = "student";
        $this->mainMenu = "student";
        $this->breadCrumbs = ["Peserta Didik" => route('student.index')];
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
            $this->repository->updateOrCreateStudent($student);
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
            ->addColumn('full_name', function($item) {
                return '<a href="'.route('student.show', $item->id).'" class="text-reset">'.$item->full_name.'</a>';
            })->addColumn('nisn', function($item) {
                return $item->nisn;
            })->addColumn('nipd', function($item) {
                return $item->nipd;
            })->addColumn('gender', function($item) {
                return $item->gender;
            })->addColumn('nama_rombel', function($item) {
                return $item->nama_rombel;
            })->addColumn('action', function($item) {
                return view('admin.pages.master-data.student.datatable', compact('item'));
//                return '<button class="btn btn-sm btn-danger btn-detail" data-data="{{ $item }}">Detail</button>';
            })->rawColumns(['full_name', 'action'])
            ->make(true);
    }
}
