<?php

namespace App\Services\MasterTransaction;

use App\Repositories\MasterTransaction\AttendanceRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;
use DateTime;
use stdClass;

class AttendanceService extends BaseService {

    public function __construct(AttendanceRepository $attendanceRepository)
    {
        $this->repository = $attendanceRepository;
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
}