<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Classroom
 *
 * @property int $id
 * @property int $academic_year_id
 * @property string $name
 * @property string $grade
 * @property int $capacity
 * @property int|null $teacher_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AcademicYear $academicYear
 * @property-read \App\Models\Teacher|null $teacher
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentAssignment> $studentAssignments
 * @property-read int $current_student_count
 * @property-read int $available_spots
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom query()
 * @method static \Database\Factories\ClassroomFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Classroom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'academic_year_id',
        'name',
        'grade',
        'capacity',
        'teacher_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = ['current_student_count', 'available_spots'];

    /**
     * Get the academic year this classroom belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the homeroom teacher for this classroom.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the student assignments for this classroom.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentAssignments(): HasMany
    {
        return $this->hasMany(StudentAssignment::class);
    }

    /**
     * Get the current number of students assigned to this classroom.
     *
     * @return int
     */
    public function getCurrentStudentCountAttribute(): int
    {
        return $this->studentAssignments()->count();
    }

    /**
     * Get the number of available spots in this classroom.
     *
     * @return int
     */
    public function getAvailableSpotsAttribute(): int
    {
        return max(0, $this->capacity - $this->current_student_count);
    }
}