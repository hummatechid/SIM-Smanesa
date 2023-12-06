<?php 

namespace App\Repositories\MasterTransaction;

use App\Repositories\BaseRepository;
use App\Models\Attendance;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;

class AttendanceRepository extends BaseRepository {

    // construct model
    public function __construct(Attendance $attendance)
    {
        $this->model = $attendance;    
    }

    public function countByStatusToday(string $status): int
    {
        $data = $this->model->selectRaw('COUNT(*) as total')
            ->whereDate('created_at', '=', today())
            ->where('status', $status)
            ->first();
        return $data->total;
    }

    public function getTodayCountAttendance(): Object
    {
        $data = new stdClass();
        $data->absent = $this->countByStatusToday('absent');
        $data->present = $this->countByStatusToday('present');
        $data->late = $this->countByStatusToday('late');
        $data->permit= $this->countByStatusToday('permit');

        return $data;
    }

    public function getTodayAttendance(): Object
    {
        return $this->model->whereDate('created_at', '=', today())->whereIn('status', ['present', 'late'])->get();
    }
}