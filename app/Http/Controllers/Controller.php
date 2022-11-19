<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function notAuthorizedResponse($message = array())
    {
        return $this->dpResponse(401, false, $message);
    }

    public function notEnoughPermissionResponse($message = array())
    {
        return $this->dpResponse(403, false, $message);
    }

    public function conflictResponse($message = array())
    {
        return $this->dpResponse(409, false, $message);
    }

    public function badRequestResponse($message = array())
    {
        return $this->dpResponse(400, false, $message);
    }

    public function successResponse($data = array())
    {
        return $this->dpResponse(200, true, array(), $data);
    }

    private function dpResponse($code, $success, $message, $data = array())
    {
        $apiResponse = array(
            'right_now' => date("Y-m-d H:i:s"),
            'timestamp' => time(),
            'success' => $success,
        );

        if(!empty($data)) $apiResponse['data'] = $data;
        if(!empty($message)) $apiResponse['message'] = $message;

        return response()->json($apiResponse, $code);
    }
}
