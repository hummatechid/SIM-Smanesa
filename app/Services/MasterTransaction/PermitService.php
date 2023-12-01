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
        $this->pageTitle = "Izin";
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
            ->addColumn('selection', function($item) {
                return '<input type="checkbox" name="permit" data-response="'.$item->status.'" value="'.$item->id.'" />';
            })
            ->addColumn('student', function($item) {
                return $item->student->full_name;
            })->addColumn('reason', function($item) {
                return $item->reason;
            })->addColumn('status', function($item) {
                // return $item->status;
                if($item->status == "pending") {
                    return '<span class="badge bg-secondary">Pending</span>';
                } else if($item->status == "rejected") {
                    return '<span class="badge bg-danger">Ditolak</span>';
                } else if($item->status == "accepted") {
                    return '<span class="badge bg-success">Diizinkan</span>';
                } else {
                    return '<span class="badge bg-primary">Telah Kembali</span>';
                }
            })->addColumn('action', function($item) {
                return view('admin.pages.permit.datatables-action', ['item' => $item]);
            })
            ->rawColumns(['action', 'status', 'selection'])
            ->make(true);
    }
}