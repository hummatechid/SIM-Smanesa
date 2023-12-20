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
        $this->role = $role;
    }

    // function get role user
    public function getRole(string $column, mixed $data): object | null
    {
        return $this->role->where($column, $data)->first();
    }

    public function getAllUserInOneRole(string $role, string $type = null)
    {
        if($type){
            return $this->model->with(["roles" => function ($q) use ($role){
                $q->where("name",$role);
            }])
            ->whereHas("roles", function ($q) use ($role){
                $q->where("name",$role);
            })
            ->whereNotNull("device_token")
            ->get();
        } else {
            return $this->model->with(["roles" => function ($q) use ($role){
                $q->where("name",$role);
            }])
            ->whereHas("roles", function ($q) use ($role){
                $q->where("name",$role);
            })
            ->get();
        }
    }
}