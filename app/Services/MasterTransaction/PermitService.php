<?php

namespace App\Services\MasterTransaction;

use App\Repositories\MasterTransaction\PermitRepository;
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
            ->addColumn('student', function($item) {
                return $item->student->full_name;
            })->addColumn('reason', function($item) {
                return $item->reason;
            })->addColumn('status', function($item) {
                if($item->status == "pending") {
                    return `<span class="badge bg-secondary">Pending</span>`;
                } else if($item->status == "rejected") {
                    return `<span class="badge bg-danger">Ditolak</span>`;
                } else if($item->status == "accepted") {
                    return `<span class="badge bg-success">Diterima</span>`;
                } else {
                    return `<span class="badge bg-primary">Telah Kembali</span>`;
                }
            })->addColumn('action', function($item) {
                return 
                '<div class="d-flex gap-3 justify-content-between align-items-center">
                    <a href="'+route('permit.show', $item->id)+'" class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Detail</a>
                    <button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Hapus</button>
                </div>';
            })->rawColumns(['action', 'status'])
            ->make(true);
    }
}