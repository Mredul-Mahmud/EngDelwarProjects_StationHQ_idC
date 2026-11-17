<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExComander extends Model
{
    protected $table = 'excomanders';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];
}
