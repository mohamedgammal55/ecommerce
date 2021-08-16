<?php

namespace App\Http\Middleware;
use Closure;
use App\jwtClass\AUTHORIZATION;
use App\Models\User;

class CheckHeader
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
        if($request->headers->has('Authorization')){
            $token = $request->header('Authorization') ;
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                return response()->json(['error' => 'Token is unvalid ',"token"=>$data], 401);
            }
            $user = User::where("id",$data->id)->first();
            if(isset($user->id)){
                $request->userData = $data;
                return $next($request);
            }
            return response()->json(['error' => 'Token is unvalid ',"token"=>$data], 401);
        }
        return response()->json(['error' => 'Authorization is required in header '], 422);
    }
}
