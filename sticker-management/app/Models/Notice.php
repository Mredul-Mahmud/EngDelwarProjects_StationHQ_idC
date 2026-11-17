<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $guarded = [];
    public function files()
    {
        return $this->hasMany(NoticeFile::class);
    }
}
