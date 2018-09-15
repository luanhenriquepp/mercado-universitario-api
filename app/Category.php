<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string ds_category
 * @property string cd_category
 */
class Category extends Model
{
    const SHOES  = 1;
    const CLOTHES = 2;

    protected $table = 'tb_category';
    protected $primaryKey = 'cd_category';

    protected $fillable = [
        'ds_category',
        ];
    
    /**
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];
}
