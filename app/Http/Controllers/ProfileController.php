<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    use ApiResponse;
    /**
     * Set User city
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $user = User::where('id',$id)->update($request->all());
            if(!$user) return $this->errorApiResponse(__('tooday.error'));
            return $this->successApiResponse(__('tooday.userData'), $user);

        }  catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }

    /**
     * Set User name
     *
     * @return \Illuminate\Http\Response
     */
    public function updateName(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $user = User::where('id',$id)->update($request->all());
            if(!$user) return $this->errorApiResponse(__('tooday.error'));
            return $this->successApiResponse(__('tooday.userData'), $user);

        }  catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }

        /**
     * Set User city
     *
     * @return \Illuminate\Http\Response
     */
    public function updateBio(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $user = User::where('id',$id)->update($request->all());
            if(!$user) return $this->errorApiResponse(__('tooday.error'));
            return $this->successApiResponse(__('tooday.userData'), $user);

        }  catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }


        /**
     * Set User city
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfession(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $user = User::where('id',$id)->update($request->all());
            if(!$user) return $this->errorApiResponse(__('tooday.error'));
            return $this->successApiResponse(__('tooday.userData'), $user);

        }  catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }
}
