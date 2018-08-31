<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $table = 'tb_category';
    protected $primaryKey = 'cd_category';

    protected $fillable = [
        'title',
        'ds_advertisement',
        'status',
        'price',
        'cd_user',
        'cd_category',
    ];
}
