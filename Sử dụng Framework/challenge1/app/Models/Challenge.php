<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'teacher_id',
        'hint',
        'created_at',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
