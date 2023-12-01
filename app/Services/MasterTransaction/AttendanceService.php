<?php

namespace App\Services\MasterTransaction;

use App\Repositories\AttendanceRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;

class AttendanceService extends BaseService {

    public function __construct(AttendanceRepository $attendanceRepository)
    {
        $this->repository = $attendanceRepository;
        $this->pageTitle = "Kehadiran";
        $this->mainUrl = "attendance";
        $this->mainMenu = "attendance";
    }
}