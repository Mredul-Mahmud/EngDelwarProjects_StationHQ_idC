<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    // protected $table = 'home_pages';
    protected $table = 'homes';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];
}
