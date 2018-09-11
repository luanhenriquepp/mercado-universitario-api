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
        $credentials = $request->only('email', 'password');
    
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => 'invalid_credentials'
            ], 400);
        }
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cpf' => 'required|max:14|min:11|unique:tb_user',
            'rg' => 'required|max:14|min:5|unique:tb_user',
            'email' => 'required|email|unique:tb_user',
            'password' => 'required|max:32|min:8',
            'public_place' => 'required|max:255|min:10',
            'number' => 'required',
            'birth' => ''
        ])->validate();
        
        if (!$validator) {
            return response()
                ->json([
                    'message' => $validator
                        ->toJson()
                    ,
                    400
                ]);
        }
        
        \DB::beginTransaction();
        try {
            $address = new Address();
            $address->cd_address = $request->input('cd_address');
            $address->public_place = $request->input('public_place');
            $address->number = $request->input('number');
            $address->complement = $request->input('complement');
            $address->neighborhood = $request->input('neighborhood');
            $address->cep = $request->input('cep');
            $address->cd_city = $request->input('cd_city');
            $address->save();
            
            $university = new University();
            $university->cd_university = $request->input('cd_university');
            $university->university_name = $request->input('university_name');
            $university->semester = $request->input('semester');
            $university->course = $request->input('course');
            $university->save();
            
            $user = new User();
            $user->name = $request->input('name');
            $user->birth = $request->input('birth');
            $user->cpf = $request->input('cpf');
            $user->rg = $request->input('rg');
            $user->email = $request->input('email');
            $user->cd_address = 1;
            $user->cd_university = $request->input('cd_university');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();
            \DB::commit();
            
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                User::with('universities', 'address')->paginate()
            ], 201);
            
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
        
        
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
                    'error' => false,
                    'message' => 'Usuário não autenticado'
                ], 403);
        }
        
        return $users = User::with('universities', 'address')
            ->paginate();
        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->save();
        return $user;
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        try {
            
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'user_not_found'
                ], 404);
            }
            
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            
            return response()->json([
                'token_invalid'
            ], $e->getStatusCode());
            
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            
            return response()->json([
                'token_absent'
            ], $e->getStatusCode());
            
        }
        
        return response()->json([
            'User' => $user,
        ], 200);
    }
    
}
