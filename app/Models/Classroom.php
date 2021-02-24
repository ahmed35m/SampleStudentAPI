<?php

use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classroom extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'school_id',
    ];

    /**
     * A classroom belongs to a school.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school() : BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * A classroom may have many students.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students() : HasMany
    {
        return $this->hasMany(Student::class);
    }
}
