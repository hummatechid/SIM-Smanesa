<?php 

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Treatment;

class TreatmentRepository extends BaseRepository {

    // construct model
    public function __construct(Treatment $treatment)
    {
        $this->model = $treatment;    
    }
}