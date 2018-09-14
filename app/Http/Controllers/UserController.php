<?php

namespace App\Http\Controllers;

use App\Address;
use App\University;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use JWTAuth;

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
            ], 400);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|max:255|min:3',
            'registration' => 'required|unique:tb_user|max:32|min:4',
            'cpf' => 'required|max:14|min:11|unique:tb_user',
            'rg' => 'required|max:14|min:5|unique:tb_user',
            'birth' => 'required',
            'email' => 'required|email|unique:tb_user',
            'password' => 'required|max:32|min:8',
            'public_place' => 'required|max:255|min:10',
            'number' => 'required',
            'complement' => 'max:255',
            'neighborhood' => 'required|max:255|min:5',
            'cep' => 'required|max:20|min:5',
            'university_name' => 'required|max:255|min:3',
            'course' => 'required|max:255|min:2',
            'semester' => 'required|max:100|min:2',
        ]);

        if (!$validator) {
            return response()
                ->json([
                    'success' => false,
                    'message' => $validator
                        ->toJson()
                ], 400);
        }

        \DB::beginTransaction();
        try {
            $address = new Address();
            $address->public_place = $request->input('public_place');
            $address->number = $request->input('number');
            $address->complement = $request->input('complement');
            $address->neighborhood = $request->input('neighborhood');
            $address->cep = $request->input('cep');
            $address->cd_city = $request->input('cd_city');
            $address->save();

            $university = new University();
            $university->university_name = $request->input('university_name');
            $university->semester = $request->input('semester');
            $university->course = $request->input('course');
            $university->save();

            $user = new User();
            $user->name = $request->input('name');
            $user->birth = $request->input('birth');
            $user->registration = $request->input('registration');
            $user->cpf = $request->input('cpf');
            $user->rg = $request->input('rg');
            $user->email = $request->input('email');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->cd_address = $address->cd_address;
            $user->cd_university = $university->cd_university;
            $user->save();

            \DB::commit();

            $token = JWTAuth::fromUser($user);
            return response()->json([
                'success' => true,
                'message' => 'Usuário criado com sucesso!',
                'access_token' => $token,
                'token_type' => 'bearer',
                User::with('universities', 'address')->paginate()
            ], 201);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /*$user = auth()->user();
        if ($user == false) {
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 400);
        }*/
        $users = User::with('universities', 'address')
            ->paginate();
        return view('teste', compact('users'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->save();
        return $user;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $users = User::find($id);

        return view('userid', compact('users'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        try {
            if ($user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dados do usuário autenticado',
                    'User' => $user
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
