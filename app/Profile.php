<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    const ADMIN = 1;
    const STUDENT = 2;

    protected $table =  'tb_profile';
    protected $primaryKey = 'cd_profile';

    protected $fillable = [
        'cd_profile',
        'ds_profile'
    ];
}
