<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string cd_category
 * @property array|null|string price
 * @property array|null|string ds_advertisement
 * @property array|null|string title
 * @property  int mixed
 * @property array|null|string cd_advertisement_status
 */
class Advertisement extends Model
{
    /**
     * @var string
     */
    protected $table = 'tb_advertisement';
    
    /**
     * @var string
     */
    protected $primaryKey = 'cd_advertisement';
    
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'ds_advertisement',
        'price',
        'cd_user',
        'cd_category',
        'cd_advertisement_status'
    ];
    
    /**
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'cd_user');
    }

    public function category()
    {
        return $this->hasOne(Category::class,'cd_category');
    }

    public function advertisement_status()
    {
        return $this->belongsTo(AdvertisementStatus::class,'cd_advertisement_status');
    }
}
