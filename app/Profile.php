<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string ds_profile
 * @property int cd_profile
 */
class Profile extends Model
{
    /**
     * Constants
     */
    const ADMIN = 1;
    const STUDENT = 2;
    
    /**
     * @var string
     */
    protected $table =  'tb_profile';
    protected $primaryKey = 'cd_profile';
    
    /**
     * @var array
     */
    protected $fillable = [
        'cd_profile',
        'ds_profile'
    ];
    
    /**
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
