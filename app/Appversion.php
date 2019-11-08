<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appversion extends Model
{
    protected $fillable = [
        'name', 'vesion'
    ];

    protected $hidden = [
        'id', 'created_at', 'updated_at'
    ];
}
