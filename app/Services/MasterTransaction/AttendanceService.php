<?php

namespace App\Services\MasterTransaction;

use App\Repositories\MasterTransaction\AttendanceRepository;
use App\Repositories\Settings\GeneralSettingRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;
use Carbon\Carbon;
use DateTime;
use stdClass;

class AttendanceService extends BaseService {
    private $studentRepository, $generalSettingRepository;

    public function __construct(AttendanceRepository $attendanceRepository, StudentRepository $studentRepository,
    GeneralSettingRepository $generalSettingRepository)
    {
        $this->repository = $attendanceRepository;
        $this->studentRepository = $studentRepository;
        $this->generalSettingRepository = $generalSettingRepository;
        $this->pageTitle = "Presensi";
        $this->mainUrl = "attendance";
        $this->mainMenu = "attendance";
        $this->breadCrumbs = ["Presensi" => route('attendance.index')];
    }

    public function studentLate(array|object $data, int $limit = 0, string $status = "masuk", $time = "07:00"): array|object
    {
        if($limit == 0){
            $result = collect($data)->filter(function($item) use ($status, $time){
                // check present_at not null
                if($item->present_at){
                    // Combine the date with the present time
                    $presentDateTime = new DateTime($item->present_at);

                    // Create a DateTime object for the target time
                    $targetDateTime = DateTime::createFromFormat('H:i', $time);
                    
                    // check present_at greather than time
                    if($presentDateTime > $targetDateTime){
                        return $item->status == $status;
                    }
                }
            });
        } else {
            $result = collect($data)->filter(function($item) use ($status, $time){
                // check present_at not null
                if($item->present_at){
                     // Combine the date with the present time
                    $presentDateTime = new DateTime($item->present_at);

                    // Create a DateTime object for the target time
                    $targetDateTime = DateTime::createFromFormat('H:i', $time);
                    // check present_at greather than time
                    if($presentDateTime > $targetDateTime){
                        return $item->status == $status;
                    }
                }
            })->take($limit);
        }

        return $result;
    }

    public function studentMustLate(array|object $data, int $limit = 0, string $status = "masuk", $time = "07:00", string $sort = "desc"): array|object
    {
        switch($sort){
            case 'desc':
                if($limit == 0){
                    $result = collect($data)->filter(function($item) use ($status, $time){
                        // check present_at not null
                        if($item->present_at){
                            // Combine the date with the present time
                            $presentDateTime = date('H:i',strtotime($item->present_at));
        
                            // Create a DateTime object for the target time
                            $targetDateTime = date('H:i', strtotime($time));

                            // check present_at greather than time
                            if($presentDateTime > $targetDateTime){
                                return $item->status == $status;
                            }
                        }
                    })->groupBy("student_id")->sortByDesc(function ($item){
                        return count($item);
                    });
                } else {
                    $result = collect($data)->filter(function($item) use ($status, $time){
                        // check present_at not null
                        if($item->present_at){
                              // Combine the date with the present time
                            $presentDateTime = date('H:i',strtotime($item->present_at));
        
                            // Create a DateTime object for the target time
                            $targetDateTime = date('H:i', strtotime($time));

                            // check present_at greather than time
                            if($presentDateTime > $targetDateTime){
                                return $item->status == $status;
                            }
                        }
                    })->groupBy("student_id")->sortByDesc(function ($item){
                        return count($item);
                    })->take($limit);
                }
            case 'asc':
                if($limit == 0){
                    $result = collect($data)->filter(function($item) use ($status, $time){
                        // check present_at not null
                        if($item->present_at){
                             // Combine the date with the present time
                            $presentDateTime = date('H:i',strtotime($item->present_at));
        
                            // Create a DateTime object for the target time
                            $targetDateTime = date('H:i', strtotime($time));

                            // check present_at greather than time
                            if($presentDateTime > $targetDateTime){
                                return $item->status == $status;
                            }
                        }
                    })->groupBy("student_id")->sortBy(function ($item){
                        return count($item);
                    });
                } else {
                    $result = collect($data)->filter(function($item) use ($status, $time){
                        // check present_at not null
                        if($item->present_at){
                              // Combine the date with the present time
                            $presentDateTime = date('H:i',strtotime($item->present_at));
        
                            // Create a DateTime object for the target time
                            $targetDateTime = date('H:i', strtotime($time));

                            // check present_at greather than time
                            if($presentDateTime > $targetDateTime){
                                return $item->status == $status;
                            }
                        }
                    })->groupBy("student_id")->sortBy(function ($item){
                        return count($item);
                    })->take($limit);
                }
            default:
                if($limit == 0){
                    $result = collect($data)->filter(function($item) use ($status, $time){
                        // check present_at not null
                        if($item->present_at){
                             // Combine the date with the present time
                            $presentDateTime = date('H:i',strtotime($item->present_at));
        
                            // Create a DateTime object for the target time
                            $targetDateTime = date('H:i', strtotime($time));

                            // check present_at greather than time
                            if($presentDateTime > $targetDateTime){
                                return $item->status == $status;
                            }
                        }
                    })->groupBy("student_id")->sortByDesc(function ($item){
                        return count($item);
                    });
                } else {
                    $result = collect($data)->filter(function($item) use ($status, $time){
                        // check present_at not null
                        if($item->present_at){
                              // Combine the date with the present time
                            $presentDateTime = date('H:i',strtotime($item->present_at));
        
                            // Create a DateTime object for the target time
                            $targetDateTime = date('H:i', strtotime($time));

                            // check present_at greather than time
                            if($presentDateTime > $targetDateTime){
                                return $item->status == $status;
                            }
                        }
                    })->groupBy("student_id")->sortByDesc(function ($item){
                        return count($item);
                    })->take($limit);
                }
        }

        // set result
        $item_result = [];
        $i = 1;
        foreach($result as $item)
        {
            $index = $i++;
            $name = $item[0]->student->full_name;
            $kelas = $item[0]->student->nama_rombel;
            $total = count($item);

            $result = new stdClass();
            $result->DT_RowIndex = $index;
            $result->name = $name;
            $result->kelas = $kelas;
            $result->total = $total;
            $item_result[] = $result;
        }

        return $item_result;
    }

    public function storeAttendanceApi(string|null $nipd){
        if(!$nipd){
            return response()->json([
                "status" => "error",
                "message" => "nipd tidak boleh kosong"
            ], 400);
        }

        $settings = $this->generalSettingRepository->getDataDateSetting(now());

        // get data student
        $student = $this->studentRepository->getOneByOther("nipd",$nipd);
        if(!$student){
            return response()->json([
                "status" => "error",
                "message" => "Data siswa tidak ditemukan"
            ], 404);
        }

        $now = now();
        $jam = Carbon::parse($now)->format('H:i');

        $attendance = $this->repository->getDataDateWithCondition($now, [], "student_id",$student->id, "first");
        if(!$attendance){
            return response()->json([
                "status" => "error",
                "message" => "Data absensi siswa tidak ditemukan"
            ], 404);
        }

        if($jam < ($settings ? $settings->time_end : "14:00")){
            if($attendance->present_at){
                return response()->json([
                    "status" => "error",
                    "message" => "Siswa telah melakukan absensi masuk"
                ], 400);
            }
    
            $attendance->update([
                "status" => "masuk",
                "present_at" => now()
            ]);
        } else {
            if(!$attendance->present_at){
                return response()->json([
                    "status" => "error",
                    "message" => "Siswa belum melakukan absensi masuk, tidak bisa absensi pulang!"
                ], 400);
            }

            if($attendance->return_at){
                return response()->json([
                    "status" => "error",
                    "message" => "Siswa telah melakukan absensi pulang"
                ], 400);
            }
            $attendance->update([
                "return_at" => now()
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Siswa berhasil absensi"
        ], 200);
    }

    public function storeAttendance(string|null $nipd, string|null $status = "masuk"){
        // check nipd
        if(!$nipd){
            return redirect()->back()->with("error","nipd tidak boleh kosong");
        }

        // check status
        if(!$status){
            $status = "masuk";
        }

        // get data student
        $student = $this->studentRepository->getOneByOther("nipd",$nipd);
        if(!$student){
            return redirect()->back()->with("error","Data siswa tidak ditemukan");
        }

        $now = now();
        $attendance = $this->repository->getDataDateWithCondition($now, [], "student_id",$student->id, "first");
        if(!$attendance){
            return redirect()->back()->with("error","Data absensi siswa tidak ditemukan");
        }

        $attendance->update([
            "status" => $status,
            "present_at" => now()
        ]);

        return redirect()->route('attendance.index')->with("success","Siswa berhasil absensi");
    }

    public function countPresentStudent(array|object $data, string $type = "present")
    {
        $settings = $this->generalSettingRepository->getDataDateSetting(now());

        if($type == "late"){
            $result = $data->filter(function ($item) use ($settings){
                $masuk = Carbon::parse($item->present_at)->format("H:i");
                if($masuk > ($settings ? $settings->start_time : "07:15")) return $item;
            });
        } else {
            $result = $data->filter(function ($item) use ($settings){
                $masuk = Carbon::parse($item->present_at)->format("H:i");
                if($masuk < ($settings ? $settings->start_time : "07:15")) return $item;
            });
        }

        return count($result);
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
            ->addColumn('student', function($item) {
                return '<a href="'.route('student.show', $item->student->id).'" class="text-reset">'.$item->student->full_name . ' ('.$item->student->nipd.')</a>';
            })->addColumn('class', function($item) {
                return $item->student->nama_rombel;
            })->addColumn('present', function($item) {
                return $item->status;
            })->addColumn('date', function($item) {
                return Carbon::parse($item->present_at ?? $item->created_at)->isoFormat('DD-MM-YYYY');
            })->rawColumns(['student'])
            ->make(true);
    }

     /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getReportDataDatatableV2(array|object $data) :JsonResponse
    {
        // $data = $data->sortBy(function ($item) {
        //     return $item->student->full_name;
        // })->groupBy("student_id");
        $data = $data->whereHas('student', function ($query) {
            $query->orderBy('full_name', "DESC");
        })->groupBy("student_id")->get();
        $data = $data->groupBy("student_id");

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('student', function($item) {
                dd($item);
                return '<a href="'.route('student.show', $item->student[0]->id).'" class="text-reset">'.$item->student[0]->full_name . ' ('.$item->student[0]->nipd.')</a>';
            })->addColumn('class', function($item) {
                return $item->student[0]->nama_rombel;
            })->addColumn('present', function($item) {
                return $item->filter(function($barang) {
                    return $barang->status == "masuk";
                })->count();
            })->addColumn('permit', function($item) {
                return $item->filter(function($barang) {
                    return $barang->status == "izin";
                })->count();
            })->addColumn('sick', function($item) {
                return $item->filter(function($barang) {
                    return $barang->status == "sakit";
                })->count();
            })->addColumn('alpa', function($item) {
                return $item->filter(function($barang) {
                    return $barang->status == "alpha";
                })->count();
            })->rawColumns(['student'])
            ->make(true);
    }

     /**
     * Get data for datatables in index page
     *
     * @return DataTables
     */
    public function getTimeSettingDatatable(array|object $data) :JsonResponse
    {
        $data = $data->sortByDesc("date");

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function($item) {
                return Carbon::parse($item->date)->locale('id_ID')->isoFormat("dddd, DD MMMM YYYY");
            })->addColumn('attendance', function($item) {
                return Carbon::parse($item->time_start)->format("H:i");
            })->addColumn('departure', function($item) {
                return Carbon::parse($item->time_end)->format("H:i");
            })->addColumn('action', function($item) {
                return view('admin.pages.attendance.datatable-setting', ['item' => $item]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}