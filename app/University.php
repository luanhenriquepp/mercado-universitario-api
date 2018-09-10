<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class University extends Model
{
    const PROJECAO = 1;
    const PRIMEIRO = 'PRIMEIRO SEMESTRE';
    const SEGUNDO  = 'PRIMEIRO SEMESTRE';
    const TERCEIRO = 'TERCEIRO SEMESTRE';
    const QUARTO   = 'QUARTO SEMESTRE';
    const QUINTO   = 'QUINTO SEMESTRE';
    const SEXTO    = 'SEXTO SEMESTRE';
    const SETIMO   = 'SÉTIMO SEMESTRE';
    const OITAVO   = 'OITAVO SEMESTRE';
    const NONO     = 'NONO SEMESTRE';
    const DECIMO   = 'DÉCIMO SEMESTRE';

    protected $table      = 'tb_university';
    protected $primaryKey = 'cd_university';
    
    protected $fillable = [
        'name',
        'course',
        'semester'
    ];
    
    /**
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];
    
    public function users() {
        return $this->hasMany(User::class,'','cd_university');
    }
}
