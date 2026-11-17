<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SslPayment extends Model
{
    protected $table = 'ssl_payments';

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
