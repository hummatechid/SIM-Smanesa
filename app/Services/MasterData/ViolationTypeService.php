<?php

namespace App\Services\MasterData;

use App\Http\Requests\ViolationTypeRequest;
use App\Repositories\ViolationTypeRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;

class ViolationTypeService extends BaseService {
    public function __construct(ViolationTypeRepository $violationTypeRepository)
    {
        $this->repository = $violationTypeRepository;
        $this->pageTitle = "Jenis Pelanggaran";
        $this->mainUrl = "violation-type";
        $this->mainMenu = "violation-master";
        $this->subMenu = "violation-type";
        $this->breadCrumbs = ["Jenis Pelanggaran" => route('violation-type.index')];
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
            })->addColumn('score', function($item) {
                return $item->score;
            })->addColumn('action', function($item) {
                return view('admin.pages.master-data.violation-type.datatables-action', ['item' => $item]);
            })->rawColumns(['action'])
            ->make(true);
    }
}