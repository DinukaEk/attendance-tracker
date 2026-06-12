<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model {
    protected $fillable = ['name', 'code'];

    public function students() {
        return $this->belongsToMany(Student::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }
}
