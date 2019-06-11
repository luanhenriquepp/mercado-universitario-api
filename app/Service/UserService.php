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
    public function getUser()
    {
        $id = auth()->user()->cd_user;
        return  User::with('universities', 'address.city.state')->find($id);
    }
}
