<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\University;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tb_user';
    protected $primaryKey = 'cd_user';
    
    
    protected $fillable = [
        'cd_university',
        'name',
        'cpf',
        'email',
        'password',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function universities()
    {
        return $this->belongsTo(University::class,'cd_university');
    }
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->name,
            'cd_user' => $this->cd_user,
            'cd_university' => $this->cd_university
        ];
    }
}
