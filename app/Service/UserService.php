<?php

namespace App\Service;

use App\Address;
use App\Constants\Constants;
use App\Constants\Messages;
use App\Http\Requests\RequestUser;
use App\Profile;
use App\University;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    /**
     * @return mixed
     */
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

    /**
     * @param RequestUser $request
     * @return JsonResponse
     */
    public function authUser(RequestUser $request)
    {
        $credentials = $request->only('registration', 'password');
        if ($token = JWTAuth::attempt($credentials)) {
            return $this->tokenReturn($token);
        }
        return response()->json([
            Constants::SUCCESS => false,
            Constants::MESSAGE => Messages::MSG003
        ], Response::HTTP_BAD_REQUEST);
    }

    public function getAll()
    {
        return User::with('universities', 'address.city.state', 'profile')->paginate();
    }

    /**
     * @param $id
     * @return User|User[]|Builder|Builder[]|Collection|Model|null
     */
    public function getById($id)
    {
        return User::with('universities', 'address.city.state', 'profile')->find($id);
    }

    /**
     * @param $id
     * @param RequestUser $request
     * @return JsonResponse
     */
    public function update($id, RequestUser $request)
    {
        $user = $this->getById($id);

        $user->address->public_place            = $request->input('public_place');
        $user->address->number                  = $request->input('number');
        $user->address->complement              = $request->input('complement');
        $user->address->neighborhood            = $request->input('neighborhood');
        $user->address->cep                     = $request->input('cep');
        $user->address->cd_city                 = $request->input('cd_city');

        $user->universities->university_name    = $request->input('university_name');
        $user->universities->semester           = $request->input('semester');
        $user->universities->course             = $request->input('course');

        $user->name                             = $request->input('name');
        $user->birth                            = $request->input('birth');
        $user->email                            = $request->input('email');
        $user->phone_number                     = $request->input('phone_number');
        $idProfile = auth()->user()->cd_profile;
        $user->cd_profile               = $request->input('cd_profile',$idProfile);

        if ($user->save() && $user->universities->save() && $user->address->save()) {
            return response()->json([
                Constants::SUCCESS => true,
                Constants::MESSAGE => Messages::MSG006
            ], Response::HTTP_OK);
        }

        return response()->json([
            Constants::SUCCESS => false,
            Constants::MESSAGE => Messages::MSG005
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return JsonResponse
     */
    public function getAuthUser()
    {
        try {
            if ($user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    Constants::SUCCESS      => true,
                    Constants::DATA         => $user
                ], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                Constants::SUCCESS  => false,
                Constants::MESSAGE  => Messages::MSG002,
                Constants::ERROR    => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    public function tokenReturn($token)
    {
        if ($token){
            return response()->json([
                'access_token'  => $token,
                'token_type'    => 'bearer',
                'expires_in'    => auth('api')->factory()->getTTL() * 600
            ], Response::HTTP_OK);
        }

        return response()->json([
            Constants::SUCCESS => false,
            Constants::MESSAGE => Messages::MSG002
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param RequestUser $request
     * @return Address
     */
    public function createAddress(RequestUser $request)
    {
        $address = new Address();
        $address->public_place      = $request->input('public_place');
        $address->number            = $request->input('number');
        $address->complement        = $request->input('complement');
        $address->neighborhood      = $request->input('neighborhood');
        $address->cep               = $request->input('cep');
        $address->cd_city           = $request->input('cd_city');
        $address->save();
        return $address;
    }

    /**
     * @param RequestUser $request
     * @return University
     */
    public function createUniversity(RequestUser $request)
    {
        $university = new University();
        $university->university_name    = $request->input('university_name');
        $university->semester           = $request->input('semester');
        $university->course             = $request->input('course');
        $university->save();
        return $university;
    }

    /**
     * @param RequestUser $request
     * @return JsonResponse
     */
    public function createUser(RequestUser $request)
    {
        DB::beginTransaction();
        try {
            $address = $this->createAddress($request);
            $university = $this->createUniversity($request);
            $user = new User();
            $user->name                     = $request->input('name');
            $user->birth                    = $request->input('birth');
            $user->registration             = $request->input('registration');
            $user->cpf                      = $request->input('cpf');
            $user->rg                       = $request->input('rg');
            $user->user_photo               = $request->input('user_photo');
            $user->email                    = $request->input('email');
            $user->password                 = bcrypt($request->input('password'));
            $user->password_confirmation    = bcrypt($request->input('password_confirmation'));
            $user->phone_number             = $request->input('phone_number');
            $user->cd_address               = $address->cd_address;
            $user->cd_university            = $university->cd_university;
            $user->cd_profile               = $request->input('cd_profile', Profile::STUDENT);
            $user->save();
            DB::commit();
            return response()->json([
                Constants::DATA     => $user,
                Constants::SUCCESS  => true,
                Constants::MESSAGE  => Messages::MSG001
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                Constants::SUCCESS   => false,
                Constants::MESSAGE   => Messages::MSG002,
                Constants::ERROR     => $e->getMessage()
            ],Response::HTTP_BAD_REQUEST);
        }
    }
}
