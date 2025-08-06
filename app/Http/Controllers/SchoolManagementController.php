<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentAssignment;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SchoolManagementController extends Controller
{
    /**
     * Display the main school management dashboard.
     */
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        if (!$activeYear) {
            return Inertia::render('welcome', [
                'error' => 'No active academic year found. Please contact your administrator.',
            ]);
        }

        // Get classrooms with statistics
        $classrooms = Classroom::with(['teacher', 'studentAssignments.student'])
            ->where('academic_year_id', $activeYear->id)
            ->get()
            ->map(function ($classroom) {
                $assignments = $classroom->studentAssignments;
                $maleCount = $assignments->filter(fn($a) => $a->student->gender === 'male')->count();
                $femaleCount = $assignments->filter(fn($a) => $a->student->gender === 'female')->count();
                
                return [
                    'id' => $classroom->id,
                    'name' => $classroom->name,
                    'grade' => $classroom->grade,
                    'capacity' => $classroom->capacity,
                    'teacher_name' => $classroom->teacher->full_name ?? 'Unassigned',
                    'current_count' => $assignments->count(),
                    'available_spots' => max(0, $classroom->capacity - $assignments->count()),
                    'male_count' => $maleCount,
                    'female_count' => $femaleCount,
                    'gender_balance' => $assignments->count() > 0 
                        ? round(($maleCount / $assignments->count()) * 100, 1)
                        : 0,
                ];
            })
            ->groupBy('grade');

        // Get unassigned students by grade
        $unassignedStudents = Student::whereDoesntHave('currentAssignment')
            ->where('status', 'active')
            ->get()
            ->groupBy('grade')
            ->map(function ($students) {
                return $students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'full_name' => $student->full_name,
                        'student_id' => $student->student_id,
                        'gender' => $student->gender,
                        'age' => $student->age,
                        'grade' => $student->grade,
                    ];
                });
            });

        // Get overall statistics
        $totalStudents = Student::where('status', 'active')->count();
        $totalAssigned = StudentAssignment::where('academic_year_id', $activeYear->id)->count();
        $totalClassrooms = Classroom::where('academic_year_id', $activeYear->id)->count();

        return Inertia::render('welcome', [
            'academicYear' => $activeYear,
            'classrooms' => $classrooms,
            'unassignedStudents' => $unassignedStudents,
            'statistics' => [
                'total_students' => $totalStudents,
                'total_assigned' => $totalAssigned,
                'total_unassigned' => $totalStudents - $totalAssigned,
                'total_classrooms' => $totalClassrooms,
                'assignment_percentage' => $totalStudents > 0 
                    ? round(($totalAssigned / $totalStudents) * 100, 1)
                    : 0,
            ],
        ]);
    }

    /**
     * Assign a student to a classroom.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        $classroom = Classroom::with('academicYear')->findOrFail($request->classroom_id);

        // Check if classroom has capacity
        $currentCount = StudentAssignment::where('classroom_id', $classroom->id)->count();
        if ($currentCount >= $classroom->capacity) {
            return back()->withErrors(['message' => 'Classroom is at full capacity.']);
        }

        // Check if student is already assigned for this academic year
        $existingAssignment = StudentAssignment::where('student_id', $student->id)
            ->where('academic_year_id', $classroom->academicYear->id)
            ->first();

        if ($existingAssignment) {
            return back()->withErrors(['message' => 'Student is already assigned to a classroom for this academic year.']);
        }

        // Create the assignment
        StudentAssignment::create([
            'student_id' => $student->id,
            'classroom_id' => $classroom->id,
            'academic_year_id' => $classroom->academicYear->id,
            'assigned_at' => now(),
            'assigned_by' => auth()->id(),
        ]);

        return redirect()->route('welcome')->with('success', "Student {$student->full_name} has been assigned to {$classroom->name}.");
    }
}