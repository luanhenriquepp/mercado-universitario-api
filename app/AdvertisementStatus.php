<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string ds_advertisement_status
 * @property string cd_advertisement_status
 */
class AdvertisementStatus extends Model
{
    const APPROVED = 'APV';
    const AWAITINGAPPROVAL = 'AAP';
    const REPROVED = 'RPV';
    const CANCELED = 'CAN';

    protected $keyType = 'string';
    protected $primaryKey = 'cd_advertisement_status';
    protected $table = 'tb_advertisement_status';

    protected $fillable = [
        'cd_advertisement_status',
        'ds_advertisement_status'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function advertisement()
    {
        return $this->hasMany(Advertisement::class);
    }
}
