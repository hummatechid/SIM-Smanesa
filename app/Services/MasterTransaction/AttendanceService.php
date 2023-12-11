<?php

namespace App\Services\MasterTransaction;

use App\Repositories\MasterTransaction\AttendanceRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;
use DateTime;

class AttendanceService extends BaseService {

    public function __construct(AttendanceRepository $attendanceRepository)
    {
        $this->repository = $attendanceRepository;
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
}