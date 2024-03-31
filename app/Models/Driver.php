<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone', 'name', 'balance'
    ];

    protected $attributes = [
        'balance' => 0,
    ];

    public $timestamps = false; 
}
