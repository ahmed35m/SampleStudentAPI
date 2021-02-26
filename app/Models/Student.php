<?php

namespace App;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class Student extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $fillable = [
        'student_number',
        'first_name',
        'last_name',
        'classroom_id',
    ];

    /**
     * A student belongs to a classroom.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom() : BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
