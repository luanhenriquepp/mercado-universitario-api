<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'created_at','updated_at'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
