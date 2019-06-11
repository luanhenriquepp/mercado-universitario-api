<?php

namespace App\Service;

use App\User;
use Illuminate\Support\Facades\DB;

class UserService
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        $id = auth()->user()->cd_user;
        return $user = User::with('address.city.state', 'universities')->find($id)->relationsToArray();
    }
}
