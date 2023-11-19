<?php 

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\ViolationType;

class ViolationTypeRepository extends BaseRepository {

    // construct model
    public function __construct(ViolationType $violationType)
    {
        $this->model = $violationType;    
    }
}