<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Traits\ApiResponse;

class PassportAuthController extends Controller
{
    use ApiResponse;
    /**
     * Registration
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            $data = $request->all();
            $user = User::with('cities:id,city')->where('uid', $data['uid'])->get()->toArray();
            # Check whether user exists in database
            if (count($user)) {
                $token = $this->login($request);
                if ($token) {
                    $oldData = ["user" => $user, "token" => $token];
                    return $this->successApiResponse(__('tooday.loggedIn'), $oldData);
                } else {
                    return $this->unprocessableApiResponse(__('tooday.notLoggedIn'));
                }
            }
            # If user doesn't exists create a new user
            $data['password'] = bcrypt($data['email'].$data['uid']);
            $user = User::create($data);
            $token = $user->createToken('tooday_token')->accessToken;
            if (!$token) {
                return $this->unprocessableApiResponse(__('tooday.error'));
            }
            $data = ["user" => $user, "token" => $token];
            return $this->successApiResponse(__('tooday.adduser'), $data);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $RequestData = $request->all();
        $RequestData['password'] = $RequestData['email'].$RequestData['uid'];

        $data = [
            'email' => $RequestData['email'],
            'password' => $RequestData['password']
        ];
        # Log in user to database
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('tooday_token')->accessToken;
            return $token;
        } else {
            return null;
        }
    }
}
