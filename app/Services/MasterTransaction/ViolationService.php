<?php

namespace App\Services\MasterTransaction;

use App\Repositories\MasterTransaction\ViolationRepository;
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
                return $item->student->full_name;
            })->addColumn('violation', function($item) {
                return $item->violationType->name;
            })->addColumn('phone_number', function($item) {
                return $item->score;
            })->addColumn('date', function($item) {
                return Carbon::parse($item->created_at)->isoFormat('DD-MM-YYYY');
            })
            ->make(true);
    }

    /**
     * Get
     *
     * @return DataTables
     */
    public function getDataGroupStudent(array|object $data, string $sort = "desc", int $limit = 0): array|object
    {
        switch($sort){
            case "desc":
                if($limit == 0){
                    // not limited
                    $data = collect($data)->groupBy("student_id")->sortByDesc(function ($item){
                        return count($item);
                    });
                } else {
                    // limit data
                    $data = collect($data)->groupBy("student_id")->sortByDesc(function ($item){
                        return count($item);
                    })->take($limit);
                }
                break;
            case "high":
                if($limit == 0){
                    // not limited
                    $data = collect($data)->groupBy("student_id")->sortByDesc(function ($item){
                        return count($item);
                    });
                } else {
                    // limit data
                    $data = collect($data)->groupBy("student_id")->sortByDesc(function ($item){
                        return count($item);
                    })->take($limit);
                }
                break;
            case "low":
                if($limit == 0){
                    // not limited
                    $data = collect($data)->groupBy("student_id")->sortBy(function ($item){
                        return count($item);
                    });
                } else {
                    // limit data
                    $data = collect($data)->groupBy("student_id")->sortBy(function ($item){
                        return count($item);
                    })->take($limit);
                }
                break;
            case "asc":
                if($limit == 0){
                    // not limited
                    $data = collect($data)->groupBy("student_id")->sortBy(function ($item){
                        return count($item);
                    });
                } else {
                    // limit data
                    $data = collect($data)->groupBy("student_id")->sortBy(function ($item){
                        return count($item);
                    })->take($limit);
                }
                break;
            default:
                if($limit == 0){
                    // not limited
                    $data = collect($data)->groupBy("student_id")->sortByDesc(function ($item){
                        return count($item);
                    });
                } else {
                    // limit data
                    $data = collect($data)->groupBy("student_id")->sortByDesc(function ($item){
                        return count($item);
                    })->take($limit);
                }
                break;
        }
        return $data;
    }
}