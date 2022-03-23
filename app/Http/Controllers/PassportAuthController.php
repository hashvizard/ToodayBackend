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
            $user = User::create($request->all());

            $token = $user->createToken('LaravelAuthApp')->accessToken;
            if (!$token) {
                return $this->unprocessableApiResponse(__('tooday.error'));
            }

            $data=["user"=>$user,"token"=>$token];

            return $this->successApiResponse(__('tooday.adduser'), $data);

        } catch (\Exception $e) {
            return $this->errorApiResponse(__('tooday.error'));
        }
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
