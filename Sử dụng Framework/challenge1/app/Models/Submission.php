<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_path',
        'file_name',
        'file_size',
        'file_original_name',
        'file_stored_name',
        'submitted_at',
    ];

    protected $appends = ['file_name', 'file_path'];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function getFileNameAttribute()
    {
        return $this->attributes['file_name']
            ?? $this->attributes['file_original_name']
            ?? null;
    }

    public function getFilePathAttribute()
    {
        if (!empty($this->attributes['file_path'])) {
            return $this->attributes['file_path'];
        }

        if (!empty($this->attributes['file_stored_name'])) {
            return 'submissions/' . ltrim($this->attributes['file_stored_name'], '/');
        }

        return null;
    }
}
