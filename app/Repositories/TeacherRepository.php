<?php 

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Teacher;

class TeacherRepository extends BaseRepository {

    // construct model
    public function __construct(Teacher $teacher)
    {
        $this->model = $teacher;    
    }
}