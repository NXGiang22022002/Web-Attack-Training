<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    protected $table = 'user';

    protected $fillable = [
        'tendangnhap','matkhau','hoten','email','sodienthoai','isteacher'
    ];

    protected $hidden = [
        'matkhau','remember_token'
    ];

    public function getAuthPassword()
    {
        return $this->matkhau;
    }
    public function sentMessages()
    {   
        return $this->hasMany(\App\Models\Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(\App\Models\Message::class, 'receiver_id');
    }
}