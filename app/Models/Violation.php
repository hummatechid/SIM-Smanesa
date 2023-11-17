<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student() {
        return $this->belongsTo(Student::class, "student_id", "id");
    }

    public function violationType() {
        return $this->belongsTo(ViolationType::class, "violation_type_id", "id");
    }
}
