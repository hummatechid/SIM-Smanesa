<?php 

namespace App\Repositories\MasterData;

use App\Repositories\BaseRepository;
use App\Models\Permit;

class PermitRepository extends BaseRepository {

    // construct model
    public function __construct(Permit $permit)
    {
        $this->model = $permit;    
    }

    // function get today with condition
    public function listTodayWithCondition(string $column, mixed $data, bool $history = null): object
    {
        switch($history)
        {
            case true: 
                return $this->model->with("student")->whereDate('created_at', date('Y-m-d'))
                ->whereNotNull('deleted_at')->where($column, $data)->get();
            case false:
                return $this->model->with("student")->whereDate('created_at', date('Y-m-d'))
                ->whereNull('deleted_at')->where($column, $data)->get();
            default:
                return $this->model->with("student")->whereDate('created_at', date('Y-m-d'))
                ->whereNull('deleted_at')->where($column, $data)->get();
        }
    }


    // function for get today
    public function listToday(bool $history = null): object
    {
        switch($history)
        {
            case true: 
                return $this->model->with("student")->whereDate('created_at', date('Y-m-d'))
                ->whereNotNull('deleted_at')->get();
            case false:
                return $this->model->with("student")->whereDate('created_at', date('Y-m-d'))
                ->whereNull('deleted_at')->get();
            default:
                return $this->model->with("student")->whereDate('created_at', date('Y-m-d'))
                ->whereNull('deleted_at')->get();
        }
    }
}