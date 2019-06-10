<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'status' => 'Token inválido'
                ],Response::HTTP_BAD_REQUEST);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json([
                    'success' => false,
                    'status' => 'Token expirado'
                ],Response::HTTP_BAD_REQUEST);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Token não encontrado'
                ],Response::HTTP_BAD_REQUEST);
            }
        }
        return $next($request);
    }
}
