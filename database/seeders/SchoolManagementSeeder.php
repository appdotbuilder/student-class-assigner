<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentAssignment;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create academic years
        $academicYear2023 = AcademicYear::create([
            'name' => '2023-2024',
            'start_date' => '2023-09-01',
            'end_date' => '2024-06-30',
            'is_active' => false,
        ]);

        $academicYear2024 = AcademicYear::create([
            'name' => '2024-2025',
            'start_date' => '2024-09-01',
            'end_date' => '2025-06-30',
            'is_active' => true,
        ]);

        // Create teachers
        $teachers = collect();
        for ($i = 1; $i <= 12; $i++) {
            $teachers->push(Teacher::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'employee_id' => 'T' . str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                'status' => 'active',
            ]));
        }

        // Create admin user
        $adminUser = User::create([
            'name' => 'School Admin',
            'email' => 'admin@school.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create principal user
        $principalUser = User::create([
            'name' => 'School Principal',
            'email' => 'principal@school.edu',
            'password' => Hash::make('password'),
            'role' => 'principal',
            'email_verified_at' => now(),
        ]);

        // Create teacher users
        $teacherUsers = collect();
        foreach ($teachers->take(3) as $index => $teacher) {
            $user = User::create([
                'name' => $teacher->full_name,
                'email' => "teacher{$index}@school.edu",
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'teacher_id' => $teacher->id,
                'email_verified_at' => now(),
            ]);
            $teacherUsers->push($user);
        }

        // Create classrooms for current academic year
        $classrooms = collect();
        $sections = ['A', 'B'];
        
        for ($grade = 1; $grade <= 6; $grade++) {
            foreach ($sections as $section) {
                $teacher = $teachers->shift() ?? null;
                
                $classroom = Classroom::create([
                    'academic_year_id' => $academicYear2024->id,
                    'name' => "Grade {$grade}{$section}",
                    'grade' => "Grade {$grade}",
                    'capacity' => fake()->numberBetween(20, 25),
                    'teacher_id' => $teacher?->id,
                ]);
                
                $classrooms->push($classroom);
            }
        }

        // Create students
        $students = collect();
        for ($grade = 1; $grade <= 6; $grade++) {
            // Create 40-50 students per grade
            $studentsPerGrade = fake()->numberBetween(40, 50);
            
            for ($i = 1; $i <= $studentsPerGrade; $i++) {
                $student = Student::create([
                    'student_id' => 'S' . $grade . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'date_of_birth' => fake()->dateTimeBetween(
                        '-' . (12 - $grade + 6) . ' years',
                        '-' . (12 - $grade + 5) . ' years'
                    ),
                    'gender' => fake()->randomElement(['male', 'female']),
                    'grade' => "Grade {$grade}",
                    'notes' => fake()->optional(0.3)->sentence(),
                    'status' => 'active',
                ]);
                
                $students->push($student);
            }
        }

        // Assign some students to classrooms (leave some unassigned for demonstration)
        foreach ($classrooms as $classroom) {
            $gradeStudents = $students->filter(function ($student) use ($classroom) {
                return $student->grade === $classroom->grade;
            });
            
            // Assign 60-80% of classroom capacity
            $assignmentCount = (int) ($classroom->capacity * fake()->numberBetween(60, 80) / 100);
            $studentsToAssign = $gradeStudents->shuffle()->take($assignmentCount);
            
            foreach ($studentsToAssign as $student) {
                StudentAssignment::create([
                    'student_id' => $student->id,
                    'classroom_id' => $classroom->id,
                    'academic_year_id' => $academicYear2024->id,
                    'assigned_at' => fake()->dateTimeThisYear(),
                    'assigned_by' => $adminUser->id,
                ]);
                
                // Remove from available students
                $students = $students->reject(function ($s) use ($student) {
                    return $s->id === $student->id;
                });
            }
        }
    }
}