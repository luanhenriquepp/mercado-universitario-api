<?php

namespace App\Http\Controllers;

use App\Address;
use App\University;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'invalid_credentials'
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'could_not_create_token'
            ], 500);
        }
        
        return response()->json(compact('token'));
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'cpf' => 'required|max:14|min:11|unique:tb_user',
            'rg' => 'required|max:14|min:5|unique:tb_user',
            'email' => 'required|email|unique:tb_user',
            'password' => 'required|max:32|min:8',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return response()
                ->json($validator->errors()->toJson(), 400);
        }
        
        $address = new Address();
        $address->public_place = 'AR 23 CONJUNTO 1';
        $address->number = '16';
        $address->complement = 'Perto do ponto de ônibus';
        $address->neighborhood = 'Sobradinho 2';
        $address->cep = '73064231';
        $address->cd_city = 2;
        $address->save();
        
        $university = new University();
        $university->cd_university = University::PROJECAO;
        $university->name = 'asd';
        $university->semester = University::QUARTO;
        $university->course = 'asd';
        $university->save();
        
        $user = new User();
        $user->name = $request->input('name');
        $user->birth = $request->input('birth');
        $user->cpf = $request->input('cpf');
        $user->rg = $request->input('rg');
        $user->email = $request->input('email');
        $user->cd_address = 1;
        $user->cd_university = University::PROJECAO;
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        
        $token = JWTAuth::fromUser($user);
        
        return response()->json([
            $token
        ], 201);
        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        $dataUser = User::with('universities', 'address')->
        where('cd_user', '=', $user->cd_user)
            ->paginate();
        return $dataUser;
        
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
            
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            
            return response()->json([
                'token_expired'
            ], $e->getStatusCode());
            
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
            $user
        ]);
    }
}
