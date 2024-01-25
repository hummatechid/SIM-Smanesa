<?php 

namespace App\Repositories\Settings;

use App\Models\GeneralSetting;
use App\Repositories\BaseRepository;

class GeneralSettingRepository extends BaseRepository {

    // construct model
    public function __construct(GeneralSetting $generalSetting)
    {
        $this->model = $generalSetting;
    }

    public function getDataDateSetting($date, array $relations = [], bool $history = false): object | null
    {
        if($history == true){
            return $this->model->with($relations)->whereNotNull("deleted_at")->whereDate("date",$date)->first(); 
        } else {
            return $this->model->with($relations)->whereNull("deleted_at")->whereDate("date",$date)->first(); 
        } 
    }
}