<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $student_id
 * @property int $subject_id
 * @property \Illuminate\Support\Carbon $date
 * @property bool $present
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance wherePresent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Attendance extends Model {
    protected $fillable = ['student_id', 'subject_id', 'date', 'present'];
    protected $casts = ['date' => 'date', 'present' => 'boolean'];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
