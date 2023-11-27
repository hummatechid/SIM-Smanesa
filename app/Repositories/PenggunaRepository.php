<?php 

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Pengguna;

class PenggunaRepository extends BaseRepository {

    // construct model
    public function __construct(Pengguna $pengguna)
    {
        $this->model = $pengguna;    
    }

    public function byIdWithRole(string $id)
    {
        return $this->model->with(['user' => function($q) {
            $q->with('roles');
        }])->where('id', $id)->firstOrFail();
    }
}