<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class University extends Model
{
    
    protected $table = 'tb_universities';
    protected $primaryKey = 'cd_university';
    
    protected $fillable = [
        'name',
        'course',
    ];
    
    public function users() {
        return $this->hasMany(User::class,'','cd_university');
    }
}
