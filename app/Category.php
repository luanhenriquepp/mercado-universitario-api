<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const SHOES  = 'SHO';
    const CLOTHES = 'CTS';
    const BOOKS = 'BKS';
    const ELETRONICS = 'ELE';

    protected $table = 'tb_category';
    protected $primaryKey = 'cd_category';
    protected $keyType = 'string';

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
