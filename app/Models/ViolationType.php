<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function violation() {
        return $this->hasMany(Violation::class, 'violation_type_id', 'id');
    }
}
