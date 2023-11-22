<?php 

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository {

    // construct model
    public function __construct(Role $role)
    {
        $this->model = $role;    
    }
}