<?php

namespace App\Services\MasterTransaction;

use App\Repositories\MasterTransaction\ViolationRepository;
use App\Repositories\PenggunaRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;
use Carbon\Carbon;
use stdClass;

class ViolationService extends BaseService {
    private $teacherRepository, $penggunaRepository;

    public function __construct(ViolationRepository $violationRepository, TeacherRepository $teacherRepository, PenggunaRepository $penggunaRepository)
    {
        $this->repository = $violationRepository;
        $this->teacherRepository = $teacherRepository;
        $this->penggunaRepository = $penggunaRepository;
        $this->pageTitle = "Pelanggaran";
        $this->mainUrl = "violation";
        $this->mainMenu = "violation";
        $this->breadCrumbs = ["Pelanggaran" => route('violation.index')];
    }

    /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getDataDatatable(array|object $data) :JsonResponse
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($item) {
                return '<a href="'.route('student.show', $item->student->id).'" class="text-reset">'.$item->student->full_name . ' ('.$item->student->nisn.')</a>';
            })->addColumn('violation', function($item) {
                return $item->violationType->name;
            })->addColumn('phone_number', function($item) {
                return $item->score;
            })->addColumn('date', function($item) {
                return Carbon::parse($item->created_at)->isoFormat('DD-MM-YYYY');
            })->addColumn('user_created', function($item) {
                return $item->user_created ? $item->user_created->full_name : "-";
            })->addColumn('user_updated', function($item) {
                return $item->user_updated ? $item->user_updated->full_name : "-";
            })->addColumn('action', function($item) {
                return view('admin.pages.violation.datatables-action', ['item' => $item]);
            })
            ->rawColumns(['action', 'student'])
            ->make(true);
    }

    
    /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getReportDataDatatable(array|object $data) :JsonResponse
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($item) {
                return '<a href="'.route('student.show', $item->student->id).'" class="text-reset">'.$item->student->full_name . ' ('.$item->student->nisn.') | '.$item->student->nama_rombel.'</a>';
            })->addColumn('violation', function($item) {
                return $item->violationType->name;
            })->addColumn('score', function($item) {
                return $item->score;
            })->addColumn('date', function($item) {
                return Carbon::parse($item->created_at)->isoFormat('DD-MM-YYYY');
            })->rawColumns(['name'])
            ->make(true);
    }

    /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getReportDataDatatableV2(array|object $data) :JsonResponse
    {
        $data = $data->sortBy(function ($item) {
            return $item->student->full_name;
        })->groupBy('student_id');

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($item) {
                return '<a href="'.route('student.show', $item[0]->student->id).'" class="text-reset">'.$item[0]->student->full_name . ' ('.$item[0]->student->nisn.')</a>';
            })->addColumn('class', function($item) {
                return $item[0]->student->nama_rombel;
            })->addColumn('violation_score', function($item) {
                return collect($item)->sum("score");
            })->addColumn('violation_total', function($item) {
                return count($item);
            })->addColumn('action', function($item) {
                return view('admin.pages.violation.datatables-report-action', ['item' => $item[0]]);
            })->rawColumns(['action', 'name'])
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

        // set result
        $item_result = [];
        $i = 1;
        foreach($data as $item){
            $index = $i++;
            $name = $item[0]->student->full_name;
            $kelas = $item[0]->student->nama_rombel;
            $poin = $item[0]->student->score;
            $total = count($item);

            $data = new stdClass();
            $data->DT_RowIndex = $index;
            $data->name = $name;
            $data->kelas = $kelas;
            $data->poin = $poin;
            $data->total = $total;
            $item_result[] = $data;
        }
        
        return $item_result;
    }
}