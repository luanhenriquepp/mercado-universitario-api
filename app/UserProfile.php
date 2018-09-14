<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @property int cd_profile
 * @property mixed cd_user
 */
class UserProfile extends Model
{
    protected $primaryKey = 'cd_user_profile';
    protected $table = 'tb_user_profile';

    protected $fillable =[
        'cd_profile',
        'cd_user'
    ];

}
