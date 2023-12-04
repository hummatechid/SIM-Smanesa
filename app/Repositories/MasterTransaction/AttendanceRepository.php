<?php 

namespace App\Repositories\MasterTransaction;

use App\Repositories\BaseRepository;
use App\Models\Attendance;

class AttendanceRepository extends BaseRepository {

    // construct model
    public function __construct(Attendance $attendance)
    {
        $this->model = $attendance;    
    }
}