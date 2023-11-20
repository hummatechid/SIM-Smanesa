<?php

namespace App\Services;

use App\Repositories\ViolationRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;
use Carbon\Carbon;

class ViolationService extends BaseService {

    public function __construct(ViolationRepository $violationRepository)
    {
        $this->repository = $violationRepository;
        $this->pageTitle = "Pelanggaran";
        $this->mainUrl = "violation";
        $this->mainMenu = "violation";
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
                return $item->student->name;
            })->addColumn('violation', function($item) {
                return $item->violation_type->name;
            })->addColumn('phone_number', function($item) {
                return $item->score;
            })->addColumn('date', function($item) {
                return Carbon::parse($item->created_at)->isoFormat('DD-MM-YYYY');
            })
            ->make(true);
    }
}