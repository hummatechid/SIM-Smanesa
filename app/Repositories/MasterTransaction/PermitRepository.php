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
}