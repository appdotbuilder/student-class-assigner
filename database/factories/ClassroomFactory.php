<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Classroom>
     */
    protected $model = Classroom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gradeNum = fake()->numberBetween(1, 6);
        $section = fake()->randomElement(['A', 'B', 'C']);
        
        return [
            'academic_year_id' => AcademicYear::factory(),
            'name' => "Grade {$gradeNum}{$section}",
            'grade' => "Grade {$gradeNum}",
            'capacity' => fake()->numberBetween(20, 30),
            'teacher_id' => Teacher::factory(),
        ];
    }

    /**
     * Indicate that the classroom has no assigned teacher.
     *
     * @return static
     */
    public function withoutTeacher()
    {
        return $this->state(fn (array $attributes) => [
            'teacher_id' => null,
        ]);
    }
}