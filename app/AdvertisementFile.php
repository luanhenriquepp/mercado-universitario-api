<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string advertisement_photo
 * @property mixed cd_advertisement_file
 */
class AdvertisementFile extends Model
{
    protected $table = 'tb_advertisement_file';
    protected $primaryKey = 'cd_advertisement_file';

    protected $fillable = [
        'advertisement_photo'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

   /* public function advertisement()
    {
        return $this->hasMany(Advertisement::class,'cd_advertisement');
    }*/
}
