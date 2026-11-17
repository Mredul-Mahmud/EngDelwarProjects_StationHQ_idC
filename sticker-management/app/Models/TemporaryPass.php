<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
class TemporaryPass extends Model
{
     public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
