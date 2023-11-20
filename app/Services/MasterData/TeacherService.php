<?php

namespace App\Services\MasterData;

use App\Repositories\TeacherRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;

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
                '<div class="d-flex gap-2 justify-content-between align-items-center">
                    <a href="'.url('teacher/'.$item->id).'" class="btn btn-sm btn-primary">Detail</a>
                    <button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Hapus</button>
                </div>';
            })->rawColumns(['action'])
            ->make(true);
        }
    }