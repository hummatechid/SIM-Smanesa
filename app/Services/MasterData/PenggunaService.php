<?php

namespace App\Services\MasterData;

use App\Repositories\PenggunaRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;

class PenggunaService extends BaseService {

    public function __construct(PenggunaRepository $penggunaRepository)
    {
        $this->repository = $penggunaRepository;
        $this->pageTitle = "Pengguna";
        $this->mainUrl = "user";
        $this->mainMenu = "user";
        $this->breadCrumbs = ["Pengguna" => route('user.index')];
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
                '<div class="d-flex gap-2 justify-content-start align-items-center">
                    <a href="'.route('user.show',$item->id).'" class="btn btn-sm btn-primary">Detail</a>
                    <button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'">Hapus</button>
                </div>';
            })->rawColumns(['action'])
            ->make(true);
    }
}