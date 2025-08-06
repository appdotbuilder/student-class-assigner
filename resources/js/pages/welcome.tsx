import { type SharedData } from '@/types';
import { Head, Link, usePage, router } from '@inertiajs/react';
import React, { useState } from 'react';

interface AcademicYear {
    id: number;
    name: string;
    start_date: string;
    end_date: string;
    is_active: boolean;
}

interface Classroom {
    id: number;
    name: string;
    grade: string;
    capacity: number;
    teacher_name: string;
    current_count: number;
    available_spots: number;
    male_count: number;
    female_count: number;
    gender_balance: number;
}

interface Student {
    id: number;
    full_name: string;
    student_id: string;
    gender: string;
    age: number;
    grade: string;
}

interface Statistics {
    total_students: number;
    total_assigned: number;
    total_unassigned: number;
    total_classrooms: number;
    assignment_percentage: number;
}

interface WelcomeProps {
    academicYear?: AcademicYear;
    classrooms?: Record<string, Classroom[]>;
    unassignedStudents?: Record<string, Student[]>;
    statistics?: Statistics;
    error?: string;
    [key: string]: unknown;
}

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;
    const { 
        academicYear, 
        classrooms = {}, 
        unassignedStudents = {}, 
        statistics,
        error 
    } = usePage<WelcomeProps>().props;
    
    const [selectedGrade, setSelectedGrade] = useState<string>('');
    const [selectedStudent, setSelectedStudent] = useState<number | null>(null);
    const [selectedClassroom, setSelectedClassroom] = useState<number | null>(null);

    const handleAssignStudent = () => {
        if (!selectedStudent || !selectedClassroom) return;

        router.post(route('assign.student'), {
            student_id: selectedStudent,
            classroom_id: selectedClassroom,
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                setSelectedStudent(null);
                setSelectedClassroom(null);
            }
        });
    };

    const grades = Object.keys(classrooms).sort();
    const currentGradeStudents = selectedGrade ? unassignedStudents[selectedGrade] || [] : [];
    const currentGradeClassrooms = selectedGrade ? classrooms[selectedGrade] || [] : [];

    if (error) {
        return (
            <>
                <Head title="School Management System" />
                <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                    <div className="bg-white rounded-lg shadow-lg p-8 max-w-md">
                        <div className="text-center">
                            <div className="text-6xl mb-4">🏫</div>
                            <h1 className="text-2xl font-bold text-gray-900 mb-2">System Notice</h1>
                            <p className="text-gray-600 mb-6">{error}</p>
                            <Link
                                href={route('login')}
                                className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                Login as Administrator
                            </Link>
                        </div>
                    </div>
                </div>
            </>
        );
    }

    return (
        <>
            <Head title="School Management System">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
                {/* Header */}
                <header className="bg-white shadow-sm border-b border-blue-200">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center py-4">
                            <div className="flex items-center space-x-3">
                                <div className="text-3xl">🏫</div>
                                <div>
                                    <h1 className="text-2xl font-bold text-gray-900">School Management System</h1>
                                    {academicYear && (
                                        <p className="text-sm text-gray-600">Academic Year: {academicYear.name}</p>
                                    )}
                                </div>
                            </div>
                            <nav className="flex items-center space-x-4">
                                {auth.user ? (
                                    <div className="flex items-center space-x-4">
                                        <span className="text-sm text-gray-600">Welcome, {auth.user.name}</span>
                                        <Link
                                            href={route('dashboard')}
                                            className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                                        >
                                            Dashboard
                                        </Link>
                                    </div>
                                ) : (
                                    <>
                                        <Link
                                            href={route('login')}
                                            className="text-blue-600 hover:text-blue-800 px-3 py-2 rounded-lg hover:bg-blue-50 transition-colors"
                                        >
                                            Log in
                                        </Link>
                                        <Link
                                            href={route('register')}
                                            className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                                        >
                                            Register
                                        </Link>
                                    </>
                                )}
                            </nav>
                        </div>
                    </div>
                </header>

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    {!auth.user ? (
                        /* Welcome Section for Non-Authenticated Users */
                        <div className="text-center mb-12">
                            <div className="bg-white rounded-xl shadow-lg p-8 mb-8">
                                <div className="text-6xl mb-6">📚🎓</div>
                                <h2 className="text-4xl font-bold text-gray-900 mb-4">
                                    Transform Your School's Class Assignment Process
                                </h2>
                                <p className="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                                    Streamline student-to-class assignments with our intuitive management system. 
                                    Perfect for primary school administrators and principals.
                                </p>

                                <div className="grid md:grid-cols-3 gap-6 mb-8">
                                    <div className="bg-blue-50 rounded-lg p-6">
                                        <div className="text-3xl mb-3">👥</div>
                                        <h3 className="text-lg font-semibold mb-2">Smart Assignment</h3>
                                        <p className="text-gray-600">Drag-and-drop interface for easy student-to-classroom assignments with real-time capacity tracking.</p>
                                    </div>
                                    <div className="bg-green-50 rounded-lg p-6">
                                        <div className="text-3xl mb-3">📊</div>
                                        <h3 className="text-lg font-semibold mb-2">Gender Balance Analytics</h3>
                                        <p className="text-gray-600">Monitor gender distribution across classrooms to ensure balanced learning environments.</p>
                                    </div>
                                    <div className="bg-purple-50 rounded-lg p-6">
                                        <div className="text-3xl mb-3">📋</div>
                                        <h3 className="text-lg font-semibold mb-2">Comprehensive Reports</h3>
                                        <p className="text-gray-600">Generate and print detailed class lists and assignment reports for administrators.</p>
                                    </div>
                                </div>

                                <div className="flex justify-center space-x-4">
                                    <Link
                                        href={route('register')}
                                        className="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition-colors"
                                    >
                                        Get Started Free
                                    </Link>
                                    <Link
                                        href={route('login')}
                                        className="border border-blue-600 text-blue-600 px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-50 transition-colors"
                                    >
                                        Sign In
                                    </Link>
                                </div>
                            </div>

                            {statistics && (
                                <div className="bg-white rounded-xl shadow-lg p-6">
                                    <h3 className="text-2xl font-bold text-gray-900 mb-6">Current Academic Year Overview</h3>
                                    <div className="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                                        <div className="bg-blue-50 rounded-lg p-4">
                                            <div className="text-2xl font-bold text-blue-600">{statistics.total_students}</div>
                                            <div className="text-sm text-gray-600">Total Students</div>
                                        </div>
                                        <div className="bg-green-50 rounded-lg p-4">
                                            <div className="text-2xl font-bold text-green-600">{statistics.total_assigned}</div>
                                            <div className="text-sm text-gray-600">Assigned</div>
                                        </div>
                                        <div className="bg-orange-50 rounded-lg p-4">
                                            <div className="text-2xl font-bold text-orange-600">{statistics.total_unassigned}</div>
                                            <div className="text-sm text-gray-600">Unassigned</div>
                                        </div>
                                        <div className="bg-purple-50 rounded-lg p-4">
                                            <div className="text-2xl font-bold text-purple-600">{statistics.total_classrooms}</div>
                                            <div className="text-sm text-gray-600">Classrooms</div>
                                        </div>
                                        <div className="bg-indigo-50 rounded-lg p-4">
                                            <div className="text-2xl font-bold text-indigo-600">{statistics.assignment_percentage}%</div>
                                            <div className="text-sm text-gray-600">Complete</div>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    ) : (
                        /* Management Interface for Authenticated Users */
                        <div>
                            {/* Statistics Dashboard */}
                            {statistics && (
                                <div className="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                                    <div className="bg-white rounded-xl shadow-lg p-6 text-center">
                                        <div className="text-3xl font-bold text-blue-600">{statistics.total_students}</div>
                                        <div className="text-gray-600">Total Students</div>
                                    </div>
                                    <div className="bg-white rounded-xl shadow-lg p-6 text-center">
                                        <div className="text-3xl font-bold text-green-600">{statistics.total_assigned}</div>
                                        <div className="text-gray-600">Assigned</div>
                                    </div>
                                    <div className="bg-white rounded-xl shadow-lg p-6 text-center">
                                        <div className="text-3xl font-bold text-orange-600">{statistics.total_unassigned}</div>
                                        <div className="text-gray-600">Unassigned</div>
                                    </div>
                                    <div className="bg-white rounded-xl shadow-lg p-6 text-center">
                                        <div className="text-3xl font-bold text-purple-600">{statistics.total_classrooms}</div>
                                        <div className="text-gray-600">Classrooms</div>
                                    </div>
                                    <div className="bg-white rounded-xl shadow-lg p-6 text-center">
                                        <div className="text-3xl font-bold text-indigo-600">{statistics.assignment_percentage}%</div>
                                        <div className="text-gray-600">Complete</div>
                                    </div>
                                </div>
                            )}

                            {/* Grade Selection */}
                            <div className="mb-6">
                                <label htmlFor="grade-select" className="block text-sm font-medium text-gray-700 mb-2">
                                    Select Grade Level:
                                </label>
                                <select
                                    id="grade-select"
                                    value={selectedGrade}
                                    onChange={(e) => {
                                        setSelectedGrade(e.target.value);
                                        setSelectedStudent(null);
                                        setSelectedClassroom(null);
                                    }}
                                    className="block w-full max-w-xs px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Choose a grade...</option>
                                    {grades.map((grade) => (
                                        <option key={grade} value={grade}>{grade}</option>
                                    ))}
                                </select>
                            </div>

                            {selectedGrade && (
                                <div className="grid lg:grid-cols-2 gap-8">
                                    {/* Classrooms */}
                                    <div className="bg-white rounded-xl shadow-lg p-6">
                                        <h3 className="text-xl font-bold text-gray-900 mb-4">
                                            📚 {selectedGrade} Classrooms
                                        </h3>
                                        <div className="space-y-4">
                                            {currentGradeClassrooms.map((classroom) => (
                                                <div
                                                    key={classroom.id}
                                                    className={`border rounded-lg p-4 cursor-pointer transition-all ${
                                                        selectedClassroom === classroom.id
                                                            ? 'border-blue-500 bg-blue-50'
                                                            : 'border-gray-200 hover:border-gray-300'
                                                    }`}
                                                    onClick={() => setSelectedClassroom(
                                                        selectedClassroom === classroom.id ? null : classroom.id
                                                    )}
                                                >
                                                    <div className="flex justify-between items-start mb-2">
                                                        <div>
                                                            <h4 className="font-semibold">{classroom.name}</h4>
                                                            <p className="text-sm text-gray-600">Teacher: {classroom.teacher_name}</p>
                                                        </div>
                                                        <div className="text-right">
                                                            <div className="text-sm font-medium">
                                                                {classroom.current_count}/{classroom.capacity}
                                                            </div>
                                                            <div className="text-xs text-gray-500">
                                                                {classroom.available_spots} spots left
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="flex justify-between text-sm">
                                                        <span>👦 Male: {classroom.male_count}</span>
                                                        <span>👧 Female: {classroom.female_count}</span>
                                                        <span className="font-medium">Balance: {classroom.gender_balance}% male</span>
                                                    </div>
                                                    <div className="mt-2 bg-gray-200 rounded-full h-2">
                                                        <div
                                                            className="bg-blue-500 h-2 rounded-full transition-all"
                                                            style={{ width: `${(classroom.current_count / classroom.capacity) * 100}%` }}
                                                        ></div>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>

                                    {/* Unassigned Students */}
                                    <div className="bg-white rounded-xl shadow-lg p-6">
                                        <h3 className="text-xl font-bold text-gray-900 mb-4">
                                            👥 Unassigned {selectedGrade} Students ({currentGradeStudents.length})
                                        </h3>
                                        {currentGradeStudents.length > 0 ? (
                                            <div className="space-y-2 max-h-96 overflow-y-auto">
                                                {currentGradeStudents.map((student) => (
                                                    <div
                                                        key={student.id}
                                                        className={`border rounded-lg p-3 cursor-pointer transition-all ${
                                                            selectedStudent === student.id
                                                                ? 'border-green-500 bg-green-50'
                                                                : 'border-gray-200 hover:border-gray-300'
                                                        }`}
                                                        onClick={() => setSelectedStudent(
                                                            selectedStudent === student.id ? null : student.id
                                                        )}
                                                    >
                                                        <div className="flex justify-between items-center">
                                                            <div>
                                                                <div className="font-medium">{student.full_name}</div>
                                                                <div className="text-sm text-gray-600">
                                                                    ID: {student.student_id} • Age: {student.age}
                                                                </div>
                                                            </div>
                                                            <div className="text-lg">
                                                                {student.gender === 'male' ? '👦' : '👧'}
                                                            </div>
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>
                                        ) : (
                                            <div className="text-center text-gray-500 py-8">
                                                <div className="text-4xl mb-2">🎉</div>
                                                <p>All {selectedGrade} students have been assigned!</p>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            )}

                            {/* Assignment Action */}
                            {selectedStudent && selectedClassroom && (
                                <div className="mt-8 bg-white rounded-xl shadow-lg p-6 text-center">
                                    <h3 className="text-lg font-semibold mb-4">Ready to Assign Student</h3>
                                    <p className="text-gray-600 mb-4">
                                        Assign selected student to the chosen classroom?
                                    </p>
                                    <button
                                        onClick={handleAssignStudent}
                                        className="bg-green-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors"
                                    >
                                        ✅ Assign Student
                                    </button>
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}