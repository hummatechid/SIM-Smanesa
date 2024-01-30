<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\{User, Role};
use Carbon\Carbon;

class UserRepository extends BaseRepository
{

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
        if ($type) {
            return $this->model->with(["roles" => function ($q) use ($role) {
                $q->where("name", $role);
            }])
                ->whereHas("roles", function ($q) use ($role) {
                    $q->where("name", $role);
                })
                ->whereNotNull("device_token")
                ->get();
        } else {
            return $this->model->with(["roles" => function ($q) use ($role) {
                $q->where("name", $role);
            }])
                ->whereHas("roles", function ($q) use ($role) {
                    $q->where("name", $role);
                })
                ->get();
        }
    }

    public function getOneUserInOneRole(string $role, string $type = null)
    {
        if ($type) {
            return $this->model->with(["roles" => function ($q) use ($role) {
                $q->where("name", $role);
            }])
                ->whereHas("roles", function ($q) use ($role) {
                    $q->where("name", $role);
                })
                ->whereNotNull("device_token")
                ->first();
        } else {
            return $this->model->with(["roles" => function ($q) use ($role) {
                $q->where("name", $role);
            }])
                ->whereHas("roles", function ($q) use ($role) {
                    $q->where("name", $role);
                })
                ->first();
        }
    }

    public function getTeacherByNik(String $nik): mixed
    {
        return $this->model->where("email", $nik)->first();
    }

    public function createTeacher(array $teacher): mixed
    {
        $teacherRole = $this->role->where("name", "guru")->first();

        $password = Carbon::createFromFormat("Y-m-d", $teacher["tanggal_lahir"])->format("dmY");

        $newTeacher = $this->model->query()
            ->create([
                'role_id' => $teacherRole->id,
                'email' => $teacher['nik'],
                'password' => bcrypt($password),
            ]);

        $newTeacher->assignRole($teacherRole->name);

        return $newTeacher;
    }
}
