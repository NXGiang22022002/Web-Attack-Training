<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'due_at',
        'file_original_name',
        'file_stored_name',
    ];

    protected $appends = ['file_name', 'file_path'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'assignment_id');
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
            return 'assignments/' . ltrim($this->attributes['file_stored_name'], '/');
        }

        return null;
    }
}
