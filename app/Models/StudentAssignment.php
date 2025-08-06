<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\StudentAssignment
 *
 * @property int $id
 * @property int $student_id
 * @property int $classroom_id
 * @property int $academic_year_id
 * @property \Illuminate\Support\Carbon $assigned_at
 * @property int $assigned_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Student $student
 * @property-read \App\Models\Classroom $classroom
 * @property-read \App\Models\AcademicYear $academicYear
 * @property-read \App\Models\User $assignedBy
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentAssignment query()
 * @method static \Database\Factories\StudentAssignmentFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class StudentAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'classroom_id',
        'academic_year_id',
        'assigned_at',
        'assigned_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    /**
     * Get the student for this assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the classroom for this assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the academic year for this assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the user who made this assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}