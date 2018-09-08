<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class University extends Model
{
    const PROJECAO = 1;
    const PRIMEIRO = 1;
    const SEGUNDO = 2;
    const TERCEIRO = 3;
    const QUARTO = 4;
    const QUINTO = 5;
    const SEXTO = 6;
    const SETIMO = 7;
    const OITAVO = 8;
    const NONO = 9;
    const DECIMO = 10;

    protected $table = 'tb_university';
    protected $primaryKey = 'cd_university';
    
    protected $fillable = [
        'name',
        'course',
    ];
    
    public function users() {
        return $this->hasMany(User::class,'','cd_university');
    }
}
