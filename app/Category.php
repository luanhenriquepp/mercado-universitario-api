<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string ds_category
 * @property string cd_category
 */
class Category extends Model
{

    const FASHION = 1;

    const ELECTRONICS = 2;
    const DOMESTIC = 3;
    const SERVICES = 4;
    const MUSIC = 5;
    const CHILDREN = 6;
    const SPORTS = 7;
    const ANIMALS = 8;
    const BOOKS = 9;


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

    public function advertisement()
    {
        return $this->hasMany(Advertisement::class);
    }
}
