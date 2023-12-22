<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Teacher;
use App\Models\User;

class TeacherRepository extends BaseRepository
{

    // construct model
    public function __construct(Teacher $teacher)
    {
        $this->model = $teacher;
    }

    public function byIdWithRole(string $id)
    {
        return $this->model->with(['user' => function ($q) {
            $q->with('roles');
        }])->where('id', $id)->firstOrFail();
    }

    /**
     * update or create teacher to database
     * @param array $teacher
     */
    public function createTeacher(array $teacher, String $userId): void
    {
        $this->model->query()
            ->create(
                [
                    'user_id' => $userId,
                    'nip' => ($teacher['nip'] == null) ? '-' : $teacher['nip'],
                    'nik' => $teacher['nik'],
                    'nuptk' => ($teacher['nuptk'] == null) ? '-' : $teacher['nuptk'],
                    'full_name' => $teacher['nama'],
                    'gender' => ($teacher['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan',
                    'phone_number' => '-',
                    'address' => '-',
                    'religion' => $teacher['agama_id_str'],
                    'jenis_ptk' => $teacher['jenis_ptk_id_str'],
                    'is_dapodik' => 1,
                ]
            );
    }

    public function updateTeacher(array $teacher, User $user): void
    {
        $user->update(
            [
                'nip' => ($teacher['nip'] == null) ? '-' : $teacher['nip'],
                'nik' => $teacher['nik'],
                'nuptk' => ($teacher['nuptk'] == null) ? '-' : $teacher['nuptk'],
                'full_name' => $teacher['nama'],
                'gender' => ($teacher['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan',
                'phone_number' => '-',
                'address' => '-',
                'religion' => $teacher['agama_id_str'],
                'jenis_ptk' => $teacher['jenis_ptk_id_str'],
                'is_dapodik' => 1,
            ]
        );
    }
}
