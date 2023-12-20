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

    public function byIdWithRole(string $id)
    {
        return $this->model->with(['user' => function($q) {
            $q->with('roles');
        }])->where('id', $id)->firstOrFail();
    }

    /**
     * update or create teacher to database
     * @param array $teacher
     */
    public function updateOrCreateTeacher(array $teacher): void
    {
        $this->model->query()
        ->updateOrCreate(
            [
                'nik' => $teacher['nip'],
                'nisn' => $teacher['nisn'],
            ],
            [
            'nik' => $teacher['nip'],
            'nisn' => $teacher['nisn'],
            'nipd' => $teacher['nuptk'],
            'full_name' => $teacher['nama'],
            'gender' => ($teacher['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan',
            'phone_number' => $teacher['nomor_telepon_seluler'],
            'address' => $teacher['alamat_jalan'],
            'religion' => $teacher['agama_id_str'],
            'jenis_ptk' => $teacher['jenis_ptk'],
            'is_dapodik' => 1,
            
        ]);
    }
}