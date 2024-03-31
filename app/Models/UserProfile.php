<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone', 'otp',
    ];

    protected $casts = [
        'otp' => 'integer',
    ];

    protected $attributes = [
        'otp' => null,
    ];

     // Установка формата номера телефона
     public function setPhoneAttribute($value)
     {
         $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
     }

     public $timestamps = false; 
}
