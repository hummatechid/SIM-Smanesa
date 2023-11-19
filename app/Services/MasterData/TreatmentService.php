<?php

namespace App\Services\MasterData;

use App\Http\Requests\TreatmentRequest;
use App\Repositories\TreatmentRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;

class TreatmentService extends BaseService {

    public function __construct(TreatmentRepository $treatmentRepository)
    {
        $this->repository = $treatmentRepository;
        $this->pageTitle = "Jenis Pelanggaran";
        $this->mainUrl = "treatment";
        $this->mainMenu = "violation-master";
        $this->subMenu = "treatment";
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
            ->addColumn('category', function($item) {
                return $item->category;
            })->addColumn('score', function($item) {
                return $item->min_score." - ".$item->max_score;
            })->addColumn('treatment', function($item) {
                return $item->action;
            })->addColumn('action', function($item) {
                return '<button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Hapus</button>';
            })->rawColumns(['action'])
            ->make(true);
    }
}