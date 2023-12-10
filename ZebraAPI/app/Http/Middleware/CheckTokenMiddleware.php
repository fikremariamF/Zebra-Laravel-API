<?php
namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class CheckTokenMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {

            $token = JWTAuth::parseToken(); // Get the token from the request header
            $user = $token->authenticate(); // authenticates the user data with the token
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error' => 'Invalid token'], 401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['error' => 'Token has expired'], 401);
            }else{
                return response()->json(['error' => 'Token not found'], 401);
            }
        }

        return $next($request);
    }
}

