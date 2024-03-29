<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class ViolationType extends Model
{
    use Uuid, HasFactory;

    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $guarded = [];

    public function violation() {
        return $this->hasMany(Violation::class, 'violation_type_id', 'id');
    }
}
