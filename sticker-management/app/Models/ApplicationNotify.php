<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
class ApplicationNotify extends Model
{
     public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
