<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {
    protected $fillable = ['student_id', 'subject_id', 'date', 'present'];
    protected $casts = ['date' => 'date', 'present' => 'boolean'];
}
