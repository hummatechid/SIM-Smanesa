<?php

namespace App\Services\MasterTransaction;

use App\Repositories\MasterTransaction\AttendanceRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;
use DateTime;
use stdClass;

class AttendanceService extends BaseService {
    private $studentRepository;

    public function __construct(AttendanceRepository $attendanceRepository, StudentRepository $studentRepository)
    {
        $this->repository = $attendanceRepository;
        $this->studentRepository = $studentRepository;
        $this->pageTitle = "Kehadiran";
        $this->mainUrl = "attendance";
        $this->mainMenu = "attendance";
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

    public function storeAttendanceApi(string $nipd){
        if(!$nipd){
            return response()->json([
                "success" => false,
                "error" => true,
                "message" => "nipd tidak boleh kosong"
            ], 400);
        }

        // get data student
        $student = $this->studentRepository->getOneByOther("nipd",$nipd);
        if(!$student){
            return response()->json([
                "success" => false,
                "error" => true,
                "message" => "Data siswa tidak ditemukan"
            ], 404);
        }

        $now = now();
        $attendance = $this->studentRepository->getDataDateWithCondition($now, [], "student_id",$student->id, "first");
        if(!$attendance){
            return response()->json([
                "success" => false,
                "error" => true,
                "message" => "Data asbsensi siswa tidak ditemukan"
            ], 404);
        }

        $attendance->update([
            "status" => "masuk",
            "present_at" => now()
        ]);

        return response()->json([
            "success" => true,
            "message" => "Siswa berhasil absensi"
        ], 200);
    }

    public function storeAttendance(string $nipd, string $status){
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
        $attendance = $this->studentRepository->getDataDateWithCondition($now, [], "student_id",$student->id, "first");
        if(!$attendance){
            return redirect()->back()->with("error","Data absensi siswa tidak ditemukan");
        }

        $attendance->update([
            "status" => $status,
            "present_at" => now()
        ]);

        return redirect()->route('attendance.index')->with("success","Siswa berhasil absensi");
    }
}