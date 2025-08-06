<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Student>
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gradeNum = fake()->numberBetween(1, 6);
        
        return [
            'student_id' => 'S' . fake()->unique()->randomNumber(6, true),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'date_of_birth' => fake()->dateTimeBetween('-12 years', '-6 years'),
            'gender' => fake()->randomElement(['male', 'female']),
            'grade' => "Grade {$gradeNum}",
            'notes' => fake()->optional()->sentence(),
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the student is male.
     *
     * @return static
     */
    public function male()
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'male',
        ]);
    }

    /**
     * Indicate that the student is female.
     *
     * @return static
     */
    public function female()
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'female',
        ]);
    }

    /**
     * Set a specific grade for the student.
     *
     * @param int $gradeNumber
     * @return static
     */
    public function grade(int $gradeNumber)
    {
        return $this->state(fn (array $attributes) => [
            'grade' => "Grade {$gradeNumber}",
        ]);
    }
}