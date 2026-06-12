<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    protected $fillable = ['name', 'registration_number'];

    public function subjects() {
        return $this->belongsToMany(Subject::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }
}
