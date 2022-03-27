<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $cities =  City::get('city')->pluck('city');
            return $this->successApiResponse(__('tooday.cities'), $cities);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }

    /**
     * Set User city
     *
     * @return \Illuminate\Http\Response
     */
    public function userCity(Request $request)
    {
        try {
            $city = $request->city;
            DB::beginTransaction();
            $cityData = City::where('city', $city)->get()->pluck('id');
            User::where('id', Auth::user()->id)->update(['city_id' => $cityData[0]]);
            $getCity = City::where('city', $city)->get(['id', 'city'])->toArray();
            DB::commit();
            return $this->successApiResponse(__('tooday.cities'), $getCity);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorApiResponse($e);
        }
    }
}
