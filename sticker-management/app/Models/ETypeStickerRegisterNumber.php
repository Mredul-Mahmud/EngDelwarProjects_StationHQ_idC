<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ETypeStickerRegisterNumber extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sticker_category_id', 
        'reg_number', 
        'created_by',
        'updated_by',
    ];

    /**
     * Get the unit for the application.
     */
    public function stickerCategory()
    {
        return $this->belongsTo(StickerCategory::class, 'sticker_category_id', 'id');
    }
}
