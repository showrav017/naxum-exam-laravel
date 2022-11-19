<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Cache;
use ReallySimpleJWT\Token;

class ValidateApiJWT_Token
{

    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        $secret = config('constants.JWT_TOKEN_SECRET'); //env("JWT_TOKEN_SECRET", "aB11*&!11AuBgd34");

        if(empty($token))
        {
            return response()->json(array(
                'right_now'=>date("Y-m-d H:i:s"),
                'timestamp'=>time(),
                'success'=>false,
                'description'=>'No Authorization Data Found'
            ), 401);
        }

        $token = str_replace("Bearer ","",$token);

        $valid = Token::validate($token, $secret);

        if(!$valid)
        {
            return response()->json(array(
                'right_now'=>date("Y-m-d H:i:s"),
                'timestamp'=>time(),
                'success'=>false,
                'description'=>'Authorization Token have been expired. 1'
            ), 401);
        }

        $payload = Token::getPayload($token, $secret);
        if(empty($payload) || (!empty($payload) && empty($payload['user_id'])))
        {
            return response()->json(array(
                'right_now'=>date("Y-m-d H:i:s"),
                'timestamp'=>time(),
                'success'=>false,
                'description'=>'Authorization Token have been expired. 2'
            ), 401);
        }

        if (!Cache::has($payload['user_id'])) {
            return response()->json(array(
                'right_now'=>date("Y-m-d H:i:s"),
                'timestamp'=>time(),
                'success'=>false,
                'description'=>'Authorization Token have been expired. 3'
            ), 401);
        }

        $loggedUserDetails = json_decode(Cache::get($payload['user_id']), true);

        $request->merge(['cache_id' => $payload['user_id'], 'isSuperAdmin'=>($loggedUserDetails['isSuperAdmin'] == 1), 'loggedUserDetails'=>$loggedUserDetails['info']]);

        return $next($request);
    }
}
