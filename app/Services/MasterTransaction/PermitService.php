<?php

namespace App\Services\MasterTransaction;

use App\Repositories\MasterData\PermitRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;

class PermitService extends BaseService {

    public function __construct(PermitRepository $permitRepository)
    {
        $this->repository = $permitRepository;
        $this->pageTitle = "Permit";
        $this->mainUrl = "permit";
        $this->mainMenu = "permit";
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
                '<div class="d-flex gap-3 justify-content-between align-items-center">
                    <button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Hapus</button>
                    <button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Hapus</button>
                </div>';
            })->rawColumns(['action'])
            ->make(true);
    }
}