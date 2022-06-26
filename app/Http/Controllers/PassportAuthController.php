<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

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
            $user = User::with('cities:id,city')->where('email', $data['email'])->get()->toArray();
            # Check whether user exists in database

            if (count($user)) {

                $token = $this->login($request);

                if ($token) {
                    if ($user[0]['cities'] == null) {
                        unset($user[0]['cities']);
                        $user[0]['city'] = null;
                    } else {
                        $user[0]['city'] = $user[0]['cities']['city'];
                        unset($user[0]['cities']);
                    }
                    $oldData = ["user" => $user, "token" => $token];
                    return $this->successApiResponse(__('tooday.loggedIn'), $oldData);
                } else {
                    return $this->unprocessableApiResponse(__('tooday.notLoggedIn'));
                }
            }
            # If user doesn't exists create a new user
            $data['password'] = bcrypt($data['email'].$data['email']);
            $user = User::create($data);

            $token = $user->createToken('tooday_token')->accessToken;
            if (!$token) {
                return $this->unprocessableApiResponse(__('tooday.error'));
            }
            $data = ["user" => [$user], "token" => $token];
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
        $RequestData['password'] = $RequestData['email'].$RequestData['email'];

        $data = [
            'email' => $RequestData['email'],
            'password' => $RequestData['password']
        ];
        # Log in user to database
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('tooday_token')->accessToken;
            return $token;
        } else {
            dd("I amm here");
            return null;
        }
    }

    /**
     * get User Data
     */
    public function user()
    {
        try {
            $id = Auth::user()->id;
            $userData =  User::with('cities:id,city')->where('id', $id)->get()->toArray();
            if ($userData[0]['cities'] == null) {
                unset($userData[0]['cities']);
                $userData[0]['city'] = null;
            } else {
                $userData[0]['city'] = $userData[0]['cities']['city'];
                unset($userData[0]['cities']);
            }
            return $this->successApiResponse(__('tooday.userData'), $userData);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }
}
