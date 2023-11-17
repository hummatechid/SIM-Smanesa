<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function attendance() {
        return $this->hasMany(Attendance::class, 'student_id', 'id');
    }

    public function permission() {
        return $this->hasMany(Permission::class, 'student_id', 'id');
    }

    public function violation(){
        return $this->hasMany(Violation::class, 'student_id', 'id');
    }
}
