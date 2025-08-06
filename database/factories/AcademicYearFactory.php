<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\AcademicYear>
     */
    protected $model = AcademicYear::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->numberBetween(2023, 2025);
        $startDate = "{$year}-09-01";
        $endDate = ($year + 1) . "-06-30";

        return [
            'name' => "{$year}-" . ($year + 1),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => false,
        ];
    }

    /**
     * Indicate that the academic year is active.
     *
     * @return static
     */
    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }
}