<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestUser;
use App\Service\UserService;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @return array
     */
    public function getCurrentUser()
    {
        return $this->service->getUserArray();
    }

    /**
     * @param RequestUser $request
     * @return JsonResponse
     */
    public function authenticate(RequestUser $request)
    {
        return $this->service->authUser($request);
    }

    /**
     * @param RequestUser $request
     */
    public function register(RequestUser $request)
    {
        $this->service->createUser($request);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function index()
    {
        return $this->service->getAll();
    }

    /**
     * @param $id
     * @return User|User[]|Builder|Builder[]|Collection|Model|null
     */
    public function show($id)
    {
       return $this->service->getById($id);
    }

    /**
     * @param $id
     * @param RequestUser $request
     * @return JsonResponse
     */
    public function update($id, RequestUser $request)
    {
        return $this->service->update($id, $request);
    }

    /**
     * @return JsonResponse
     */
    public function getAuthenticatedUser()
    {
       return $this->service->getAuthUser();
    }

}
