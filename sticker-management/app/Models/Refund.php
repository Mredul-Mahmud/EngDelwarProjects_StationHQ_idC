<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'application_id', 
       'refund_amout', 
       'transaction_id',
       'reason',
       'refund_by',
   ];
}
