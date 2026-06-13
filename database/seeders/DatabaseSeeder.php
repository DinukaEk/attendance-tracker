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

    public function run(): void
    {
        // 1. Create users first
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('admin1'),
        ]);

        $teacher1 = User::factory()->create([
            'name' => 'Teacher One',
            'email' => 'teacher1@example.com',
            'role' => 'teacher',
            'password' => bcrypt('teacher1'),
        ]);

        $teacher2 = User::factory()->create([
            'name' => 'Teacher Two',
            'email' => 'teacher2@example.com',
            'role' => 'teacher',
            'password' => bcrypt('teacher2'),
        ]);

        // 2. Create subjects WITH user_id assigned directly
        $subjectsData = [
            ['name' => 'Programming Fundamentals', 'code' => 'SIT101', 'user_id' => $teacher1->id],
            ['name' => 'Database Systems',         'code' => 'SIT102', 'user_id' => $teacher1->id],
            ['name' => 'Web Development',          'code' => 'SIT103', 'user_id' => $teacher2->id],
            ['name' => 'Networking Basics',        'code' => 'SIT104', 'user_id' => $teacher2->id],
            ['name' => 'Mathematics for IT',       'code' => 'SIT105', 'user_id' => $teacher1->id],
        ];

        $subjectIds = [];
        foreach ($subjectsData as $subject) {
            $subjectIds[] = Subject::create($subject)->id;
        }

        // 3. Create 50 students
        $students = [];
        for ($i = 1; $i <= 50; $i++) {
            $students[] = Student::create([
                'name' => 'Student ' . $i,
                'registration_number' => 'REG' . str_pad($i, 4, '0', STR_PAD_LEFT),
            ]);
        }

        // 4. Enroll each student in 3-5 random subjects
        foreach ($students as $student) {
            $numSubjects = rand(3, 5);
            $enrolledSubjects = collect($subjectIds)->random($numSubjects);
            $student->subjects()->attach($enrolledSubjects);
        }

        // 5. Seed attendance records for the past 14 days
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
                        'present' => rand(0, 100) < 80,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        foreach (array_chunk($attendanceData, 500) as $chunk) {
            DB::table('attendances')->insert($chunk);
        }
    }
}