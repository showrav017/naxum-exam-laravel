<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use ReallySimpleJWT\Token;

class WebAuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) self::badRequestResponse();;

        $validator = Validator::make(json_decode($request->getContent(), true), [
            'user_name' => 'required|string',
            'user_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->notAuthorizedResponse($validator->errors());
        } else {

            $userExists = Users::firstWhere('user_name', $data["user_name"]);

            if (empty($userExists)) {
                return $this->notAuthorizedResponse();
            }

            if (!Hash::check($data["user_password"], $userExists->password)) return $this->notAuthorizedResponse();

            $cacheID = uniqid('') . bin2hex(random_bytes(4));
            $secret = config('constants.JWT_TOKEN_SECRET');
            $expiration = (time() + intval(config("constants.TOKEN_EXPIRATION_SECOND")));
            $issuer = request()->getHost();
            $token = Token::create($cacheID, $secret, $expiration, $issuer);

            $response = [
                'isSuperAdmin' => $userExists->is_super_admin,
                'info' => $userExists
            ];

            $userExists->update(['last_logged_at' => date("Y-m-d H:i:s")]);
            $request->session()->put('logged_cache_id', $cacheID);
            $request->session()->put('AuthorizationToken', $token);

            Cache::put($cacheID, json_encode($response), config("constants.TOKEN_EXPIRATION_SECOND"));

            return $this->successResponse(array(
                'token' => $token
            ));
        }
    }

    public function logout(Request $request)
    {
        $token = $request->session()->get('AuthorizationToken', '');
        $secret = config('constants.JWT_TOKEN_SECRET');

        if (empty($token))
            return redirect('/login');

        $token = str_replace("Bearer ", "", $token);
        $valid = Token::validate($token, $secret);

        if (!$valid)
            return redirect('/login');

        $payload = Token::getPayload($token, $secret);
        if (empty($payload) || (empty($payload['user_id'])))
            return $this->notAuthorizedResponse();

        if (!Cache::has($payload['user_id']))
            return redirect('/login');

        Cache::forget($payload['user_id']);
        $request->session()->forget('AuthorizationToken');
        $request->session()->forget('logged_cache_id');

        return redirect('/login');
    }
}
