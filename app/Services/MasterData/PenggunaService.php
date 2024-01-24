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
            })->addColumn('role', function($item) {
                $roles = $item->user->roles;
                $new_roles = [];
                
                foreach($roles as $role) {
                    $new_roles[] = $role->name;
                }

                return implode(', ', $new_roles);
            })->addColumn('phone_number', function($item) {
                return ($item->phone_number || !$item->phone_number == "" ? $item->phone_number : '-');
            })->addColumn('action', function($item) {
                $button = '<div class="d-flex gap-2 justify-content-start align-items-center">
                    <a href="'.route('user.show',$item->id).'" class="btn btn-sm btn-primary" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Detail" data-bs-placement="top">
                    <i class="bi bi-list-ul"></i>
                    </a>';
                if(auth()->user()->hasRole('superadmin')) {
                    $button .= '<button class="btn btn-sm btn-danger delete-data" data-id="'.$item->id.'" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Hapus" data-bs-placement="top">
                    <i class="bi bi-trash"></i>
                    </button>';
                }
                $button .= '</div>';
                return $button;
            })->rawColumns(['action'])
            ->make(true);
    }

    public function countUser(array|object $data)
    {
        $result = collect($data)->map(function($item){
            if($item->user) return $item;
        });
        return count($result);
    }
}