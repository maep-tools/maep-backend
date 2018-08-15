<?php

namespace App\Http\Controllers\Api;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use EmailChecker;

class AuthController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * Login a user
     *
     * @param  Request $request
     * @return JSON
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response([
            'token' => $token,
            'status' => 'success'
        ])
        ->header('Authorization', $token);
    }

    public function validateEmail ($email) {
      return [EmailChecker::check($email)];
    }

    /**
     * Refresh the token
     *
     * @return JSON
     */
    public function refresh()
    {
        $token = JWTAuth::parseToken()->refresh();
        return response()->json(compact('token'));
    }

    /**
     * Register a User
     *
     * @param  Request $request
     * @return JSON
     */
    public function register(Request $request)
    {
        $data = $request->only('email', 'password', 'name', 'phone', 'company', 'lastname');

        return DB::transaction(function() use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $user = $this->service->create($user, $data['password'],'Miembro', true, $data);
            
            $token = JWTAuth::fromUser($user);

            return response()->json(compact('token'));
        });
    }
}
