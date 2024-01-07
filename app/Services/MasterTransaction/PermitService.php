<?php

namespace App\Services\MasterTransaction;

use App\Repositories\MasterTransaction\PermitRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PermitService extends BaseService {

    public function __construct(PermitRepository $permitRepository)
    {
        $this->repository = $permitRepository;
        $this->pageTitle = "Izin";
        $this->mainUrl = "permit";
        $this->mainMenu = "permit";
        $this->breadCrumbs = ['Izin' => route('permit.index')];
    }

    /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getDataDatatable(Request $request) :JsonResponse
    {
        if(!isset($request->status) || !$request->status || $request->status == "null") $data = $this->repository->getAll();
        else {
            $status = str_replace("?","",$request->status);
            $data = $this->repository->whereOneCondition('status', $status);
        } 

        $data = $data->sortByDesc("created_at");

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('selection', function($item) {
                return '<input type="checkbox" name="permit" data-response="'.$item->status.'" value="'.$item->id.'" />';
            })
            ->addColumn('student', function($item) {
                return $item->student->full_name;
            })->addColumn('reason', function($item) {
                return $item->reason;
            })->addColumn('date', function($item) {
                return Carbon::parse($item->created_at)->isoFormat('DD/MM/YYYY - HH:mm');
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