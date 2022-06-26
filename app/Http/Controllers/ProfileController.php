<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            return $this->errorApiResponse($request->all());
        /*     $user = Auth::user();
            $profilePath =$request->file(key:'aa')->store(path:'profile/'.$user->city_id.'/'.$user->uid,options:'s3');
            Storage::disk(name:'s3')->setVisibility($profilePath,visibility:'public');
            $data=['profile'=>Storage::disk(name:'s3')->url($profilePath)];

            $userData = User::where('id',$user->id)->update($data);
            if(!$userData) return $this->errorApiResponse(__('tooday.error'),"sds");
            return $this->successApiResponse(__('tooday.userData'), $userData); */

        }  catch (\Exception $e) {
            return $this->errorApiResponse("I waa");
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
