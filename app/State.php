<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table  = 'tb_state';
    protected $primaryKey = 'cd_state';

    protected $fillable = [
        'initials',
        'state_name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
