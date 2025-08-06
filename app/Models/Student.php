<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Student
 *
 * @property int $id
 * @property string $student_id
 * @property string $first_name
 * @property string $last_name
 * @property \Illuminate\Support\Carbon $date_of_birth
 * @property string $gender
 * @property string $grade
 * @property string|null $notes
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $full_name
 * @property-read int $age
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentAssignment> $assignments
 * @property-read \App\Models\StudentAssignment|null $currentAssignment
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student active()
 * @method static \Illuminate\Database\Eloquent\Builder|Student byGrade(string $grade)
 * @method static \Database\Factories\StudentFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'grade',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = ['full_name', 'age'];

    /**
     * Get the student's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the student's age.
     *
     * @return int
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    /**
     * Get all assignments for this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(StudentAssignment::class);
    }

    /**
     * Get the current assignment for this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentAssignment(): HasOne
    {
        return $this->hasOne(StudentAssignment::class)
            ->whereHas('academicYear', function ($query) {
                $query->where('is_active', true);
            });
    }

    /**
     * Scope a query to only include active students.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter by grade.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $grade
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByGrade($query, string $grade)
    {
        return $query->where('grade', $grade);
    }
}