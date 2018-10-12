<?php

namespace App\Http\Controllers;

use App\Address;
use App\Profile;
use App\University;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('registration', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Mátricula ou senha inválidos.'
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'token_type'    => 'bearer',
            'access_token'  => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $request->validate([
            'name'              => 'required|max:255|min:3',
            'registration'      => 'required|unique:tb_user|max:32|min:4',
            'cpf'               => 'required|max:14|min:11|unique:tb_user',
            'rg'                => 'required|max:14|min:5|unique:tb_user',
            'birth'             => 'required',
            'email'             => 'required|email|unique:tb_user',
            'password'          => 'required|max:32|min:8|regex: ^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$^|confirmed',
            'public_place'      => 'required|max:255|min:10',
            'number'            => 'required',
            'complement'        => 'max:255',
            'neighborhood'      => 'required|max:255|min:5',
            'cep'               => 'required|max:20|min:5',
            'university_name'   => 'required|max:255|min:3',
            'course'            => 'required|max:255|min:2',
            'semester'          => 'required|max:100|min:2',
        ]);

        \DB::beginTransaction();
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
            $user->cd_address               = $address->cd_address;
            $user->cd_university            = $university->cd_university;
            $user->cd_profile               = $request->input('cd_profile', Profile::STUDENT);
            $user->save();
            
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],Response::HTTP_BAD_REQUEST);
        }
        JWTAuth::fromUser($user);
        return response()->json([
            'success' => true,
            'message' => 'Usuário criado com sucesso!',
        ], Response::HTTP_CREATED);

    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth()->user();
        if ($user == false) {
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], Response::HTTP_UNAUTHORIZED);
        }
        return $users = User::with('universities', 'address', 'profile')
            ->paginate();
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $response = [
            'success' => false,
            'message' => 'Não foi possível atualizar os dados do usuário!'
        ];

        $request->validate([
            'name'                  => 'required|max:255|min:3',
            'password'              => 'required|max:32|min:8|regex: ^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$^|confirmed',
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
        $user->password                 = bcrypt($request->input('password'));
        $user->password_confirmation    = bcrypt($request->input('password'));

        if ($user->save() && $university->save() && $address->save()) {
            $response = [
                'success' => true,
                'message' => 'Dados do usuário foram atualizados com sucesso!'
            ];
        }
        return $response;
    }

    /**
     * @param $id
     * @return User|User[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return $user = User::with('universities', 'address', 'profile')->find($id);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        try {
            if ($user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'success'   => true,
                    'data'      => $user
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'success' => false,
            'message' => 'Ocorreu um erro inesperado'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
