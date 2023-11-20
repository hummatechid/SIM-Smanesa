<?php 

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Violation;

class ViolationRepository extends BaseRepository {

    // construct model
    public function __construct(Violation $violation)
    {
        $this->model = $violation;    
    }
}