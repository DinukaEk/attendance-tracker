<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 1. Create 5 subjects
        $subjects = [
            ['name' => 'Programming Fundamentals', 'code' => 'SIT101'],
            ['name' => 'Database Systems', 'code' => 'SIT102'],
            ['name' => 'Web Development', 'code' => 'SIT103'],
            ['name' => 'Networking Basics', 'code' => 'SIT104'],
            ['name' => 'Mathematics for IT', 'code' => 'SIT105'],
        ];

        $subjectIds = [];
        foreach ($subjects as $subject) {
            $subjectIds[] = Subject::create($subject)->id;
        }

        // 2. Create 50 students
        $students = [];
        for ($i = 1; $i <= 50; $i++) {
            $students[] = Student::create([
                'name' => 'Student ' . $i,
                'registration_number' => 'REG' . str_pad($i, 4, '0', STR_PAD_LEFT),
            ]);
        }

        // 3. Enroll each student in 3-5 random subjects
        foreach ($students as $student) {
            $numSubjects = rand(3, 5);
            $enrolledSubjects = collect($subjectIds)->random($numSubjects);
            $student->subjects()->attach($enrolledSubjects);
        }

        // 4. Seed attendance records for the past 14 days
        $attendanceData = [];
        $today = now()->startOfDay();

        foreach ($students as $student) {
            $enrolledSubjectIds = $student->subjects()->pluck('subjects.id');

            for ($day = 0; $day < 14; $day++) {
                $date = $today->copy()->subDays($day)->toDateString();

                foreach ($enrolledSubjectIds as $subjectId) {
                    $attendanceData[] = [
                        'student_id' => $student->id,
                        'subject_id' => $subjectId,
                        'date' => $date,
                        'present' => rand(0, 100) < 80, // ~80% attendance rate
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert in chunks for performance
        foreach (array_chunk($attendanceData, 500) as $chunk) {
            DB::table('attendances')->insert($chunk);
        }
    }
}