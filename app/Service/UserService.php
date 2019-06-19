<?php

namespace App\Service;

use App\User;

class UserService
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function getUserObj()
    {
        $id = auth()->user()->cd_user;
        return  User::with('universities', 'address.city.state')->find($id);
    }

     public function getUserArray()
    {
        $id = auth()->user()->cd_user;

         return DB::table('tb_user')
            ->where('cd_user','=' ,$id)
            ->join('tb_address', 'tb_user.cd_address', '=', 'tb_address.cd_address')
            ->join('tb_city', 'tb_address.cd_city', '=', 'tb_city.cd_city')
            ->join('tb_state', 'tb_city.cd_state', '=', 'tb_state.cd_state')
            ->join('tb_university', 'tb_user.cd_university', '=', 'tb_university.cd_university')
            ->get();
    }


}
