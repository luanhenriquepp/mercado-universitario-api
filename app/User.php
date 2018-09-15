<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\University;

/**
 * @property array|null|string email
 * @property string password
 * @property int cd_university
 * @property int cd_address
 * @property array|null|string rg
 * @property array|null|string cpf
 * @property array|null|string birth
 * @property array|null|string name
 * @property mixed cd_user
 * @property array|null|string registration
 * @property mixed cd_profile
 */
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
    
    /**
     * @var array
     */
    protected $fillable = [
        'cd_university',
        'cd_address',
        'name',
        'birth',
        'cpf',
        'email',
        'password',
        'cd_profile'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','created_at','updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function universities()
    {
        return $this->belongsTo(University::class,'cd_university');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class,'cd_address');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class,'cd_profile');
    }
    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->name,
            'cd_user' => $this->cd_user,
            'cd_profile'=> $this->cd_profile,
            'cd_university' => $this->cd_university,
            'cd_address' => $this->cd_address,
            'cd_profile' => $this->cd_profile
        ];
    }
}
