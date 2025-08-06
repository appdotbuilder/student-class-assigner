<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Teacher>
     */
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'employee_id' => 'T' . fake()->unique()->randomNumber(6, true),
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the teacher is inactive.
     *
     * @return static
     */
    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}