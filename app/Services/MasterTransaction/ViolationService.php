<?php

namespace App\Services\MasterTransaction;

use App\Repositories\MasterTransaction\ViolationRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Services\BaseService;

class AttendanceService extends BaseService {

    public function __construct(ViolationRepository $violationRepository)
    {
        $this->repository = $violationRepository;
        $this->pageTitle = "Pelanggaran";
        $this->mainUrl = "violation";
        $this->mainMenu = "violation";
    }
}