<?php

namespace App\Services\MasterData;

use App\Http\Requests\TreatmentRequest;
use App\Repositories\TreatmentRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class TreatmentService {
    private $treatmentRepository;
    private $pageTitle = "Jenis Pelanggaran";
    private $mainUrl = "treatment";
    private $mainMenu = "violation-master";
    private $subMenu = "treatment";

    public function __construct(TreatmentRepository $treatmentRepository)
    {
        $this->treatmentRepository = $treatmentRepository;
    }

    /**
     * Get data to showing in the page data
     *
     * @param string $subTitle
     * @param array $optionalData
     * @return array
     */
    public function getPageData(string $subTitle ,array $optionalData = []) :array
    {
        $data = array_merge([
            "page_title" => $this->pageTitle,
            "main_url" => $this->mainUrl,
            "sub_title" => $subTitle,
            "main_menu" => $this->mainMenu,
            "sub_menu" => $this->subMenu
        ], $optionalData);
        return $data;
    }

    /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getDataDatatable() :JsonResponse
    {
        $data = $this->treatmentRepository->getOrderedData("min_score", "asc");

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