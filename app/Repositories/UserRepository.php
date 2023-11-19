<?php 

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\{User, Role};

class UserRepository extends BaseRepository {

    private $role;

    // construct model
    public function __construct(User $user, Role $role)
    {
        $this->model = $user;    
    }

    // function get role user
    public function getRole(string $column, mixed $data): object | null
    {
        return $this->role->where($column, $data)->first();
    }
}