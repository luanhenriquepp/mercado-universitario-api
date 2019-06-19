<?php

namespace App\Http\Controllers;

use App\Address;
use App\Constants\Constants;
use App\Constants\Messages;
use App\Http\Requests\RequestUser;
use App\Profile;
use App\Service\UserService;
use App\University;
use App\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    protected $service;

    /**
     * AdvertisementController constructor.
     * @param UserService $service
     */
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
     * Método que autentica o usuário
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('registration', 'password');
        if ($token = JWTAuth::attempt($credentials)) {
            return $this->respondWithToken($token);
        }
        return response()->json([
            'success' => false,
            'message' => Messages::MSG003
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Método que registra um novo usuário
     * @param Request $request
     * @return JsonResponse
     */
    public function register(RequestUser $request)
    {

        DB::beginTransaction();
        try {
            $address = new Address();
            $address->public_place      = $request->input('public_place');
            $address->number            = $request->input('number');
            $address->complement        = $request->input('complement');
            $address->neighborhood      = $request->input('neighborhood');
            $address->cep               = $request->input('cep');
            $address->cd_city           = $request->input('cd_city');
            $address->save();

            $university = new University();
            $university->university_name    = $request->input('university_name');
            $university->semester           = $request->input('semester');
            $university->course             = $request->input('course');
            $university->save();

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
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => Messages::MSG002
            ],Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            Constants::DATA    =>$user,
            'success' => true,
            'message' => Messages::MSG001
        ], Response::HTTP_CREATED);
    }

    /**
     * Mostra todos os usuários do sistema
     * @return LengthAwarePaginator|JsonResponse
     */
    public function index()
    {
        $user = auth()->user();
        if ($user == false) {
            return response()
                ->json([
                    'success' => false,
                    'message' => Messages::MSG004
                ], Response::HTTP_UNAUTHORIZED);
        }
        return $users = User::with('universities', 'address.city.state', 'profile')
            ->paginate();
    }

    /**
     * Pega usuário por ID
     * @param $id
     * @return User|User[]|Builder|Builder[]|Collection|Model|null
     */
    public function show($id)
    {
        return User::with('universities', 'address.city.state', 'profile')->find($id);

    }

    /**
     * Método que atualiza os dados do usuário
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update($id, Request $request)
    {
        $request->validate([
            'name'                  => 'required|max:255|min:3',
            'public_place'          => 'required|max:255|min:10',
            'number'                => 'required',
            'complement'            => 'max:255',
            'neighborhood'          => 'required|max:255|min:5',
            'cep'                   => 'required|max:20|min:5',
            'university_name'       => 'required|max:255|min:3',
            'course'                => 'required|max:255|min:2',
            'semester'              => 'required|max:100|min:2',
            'cd_city'               => 'required'
        ]);

        $user = User::with('universities', 'address', 'profile')->find($id);
        $address = $user->address;
        $address->public_place      = $request->input('public_place');
        $address->number            = $request->input('number');
        $address->complement        = $request->input('complement');
        $address->neighborhood      = $request->input('neighborhood');
        $address->cep               = $request->input('cep');
        $address->cd_city           = $request->input('cd_city');

        $university = $user->universities;
        $university->university_name    = $request->input('university_name');
        $university->semester           = $request->input('semester');
        $university->course             = $request->input('course');

        $user->name                     = $request->input('name');
        $user->birth                    = $request->input('birth');
        $user->email                    = $request->input('email');
        $user->phone_number             = $request->input('phone_number');
        $idProfile = auth()->user()->cd_profile;
        $user->cd_profile               = $request->input('cd_profile',$idProfile);

        if ($user->save() && $university->save() && $address->save()) {
            return response()->json([
                'success' => true,
                'message' => Messages::MSG006
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success' => false,
            'message' => Messages::MSG005
        ], Response::HTTP_BAD_REQUEST);
    }


    /**
     * @return JsonResponse
     */
    public function getAuthenticatedUser()
    {
        try {
            if ($user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'success'      => true,
                    Constants::DATA         => $user
                ], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => Messages::MSG002
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Valida as informações no momento em que o usuário é criado
     * @param $request
     * @return bool|JsonResponse
     */

    /**
     * Método que gera o token
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {

        if ($token){
            return response()->json([
                'access_token'  => $token,
                'token_type'    => 'bearer',
                'expires_in'    => auth('api')->factory()->getTTL() * 600
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success' => false,
            'message' => Messages::MSG002
        ], Response::HTTP_BAD_REQUEST);
    }
}
