<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int cd_city
 * @property string cep
 * @property string neighborhood
 * @property string complement
 * @property string number
 * @property string public_place
 * @method save()
 */
class Address extends Model
{

    protected $table = 'tb_address';
    protected $primaryKey = 'cd_address';

    protected $fillable = [
        'public_place',
        'number',
        'complement',
        'neighborhood',
        'cep',
        'cd_city'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class,'cd_city', 'cd_city');
    }
}
