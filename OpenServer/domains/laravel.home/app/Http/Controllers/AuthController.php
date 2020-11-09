<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;

class AuthController extends Controller
{


    public function register(RegisterRequest $request)
    {
        /**
         * @mixin User
         */
        $user = User::create($request->validated());
        $data = $user->toArray($request) +
            ['access_token' => $user->createToken('api')->plainTextToken];
        return $this->created($data);
    }



    /**
     * @param LoginRequest $loginRequest
     * @return JsonResponse
     * @throw ValidationException
     */

    public function login(LoginRequest $loginRequest) {


        if (!Auth::once($loginRequest->validated() )) {
          throw ValidationException::withMessages([
              'email' => 'These credentials do not match our records.']);
        }
        $user = Auth::user();
        $data = $user -> toArray($loginRequest) +
            ['access_token' => $user->createToken('api')->plainTextToken];
        return $this->success($data);

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param LogoutRequest $request
     * @return JsonResponse
     */
    public function logout()
    {
        $user = Auth::user();
            $user->currentAccessToken()->delete();
            return $this->success('Successfully logged out.');
    }
}
