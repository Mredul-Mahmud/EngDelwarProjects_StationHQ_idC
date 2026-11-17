<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExSso extends Model
{
    protected $table = 'exsso';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];
}
