<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    /**
     * @var string
     */
    protected $table = 'tb_category';
    
    /**
     * @var string
     */
    protected $primaryKey = 'cd_category';
    
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'ds_advertisement',
        'status',
        'price',
        'cd_user',
        'cd_category',
    ];
    
    /**
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];
}
