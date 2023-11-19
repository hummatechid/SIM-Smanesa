<?php

namespace App\Services\MasterData;

use App\Http\Requests\ViolationTypeRequest;
use App\Repositories\ViolationTypeRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class ViolationTypeService {
    private $violationTypeRepository;
    private $pageTitle = "Jenis Pelanggaran";
    private $mainUrl = "violation-type";
    private $mainMenu = "violation-master";
    private $subMenu = "violation-type";

    public function __construct(ViolationTypeRepository $violationTypeRepository)
    {
        $this->violationTypeRepository = $violationTypeRepository;
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
        $data = $this->violationTypeRepository->getOrderedData("name", "asc");

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($item) {
                return $item->name;
            })->addColumn('score', function($item) {
                return $item->score;
            })->addColumn('action', function($item) {
                return '<button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Hapus</button>';
            })->rawColumns(['action'])
            ->make(true);
    }
}