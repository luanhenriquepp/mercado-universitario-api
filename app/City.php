<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'tb_city';
    protected $primaryKey = 'cd_city';
    protected $fillable = [
        'cd_state',
        'city_name',
        'ibge_code'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function state()
    {
        return $this->belongsTo(State::class,'cd_state', 'cd_state');
    }
}
