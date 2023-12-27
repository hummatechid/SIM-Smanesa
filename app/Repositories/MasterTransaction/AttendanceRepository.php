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
        $data->absent = $this->countByStatusToday('alpha');
        $data->present = $this->countByStatusToday('masuk');
        $data->sick = $this->countByStatusToday('sakit');
        $data->permit= $this->countByStatusToday('izin');

        return $data;
    }

    public function getTodayAttendance(int $limit = null): Object
    {
        if($limit) return $this->model->whereDate('created_at', today())->whereIn('status', ['masuk'])->limit($limit)->get();
        else return $this->model->whereDate('created_at', today())->whereIn('status', ['masuk'])->get();
    }

    public function getTodayAbsent(): Object
    {
        return $this->model->whereDate('created_at', '=', today())->whereIn('status', ['izin', 'sakit', 'alpha'])->get();
    }
}