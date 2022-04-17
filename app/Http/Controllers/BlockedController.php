<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockedController extends Controller
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
            $userId = Auth::user()->id;
            $data = Block::with('users:id,name,profile')->where('blocker_user_id',$userId)->orderBy('id', 'DESC')->get(['id','blocked_user_id','created_at'])->toArray();
            return $this->successApiResponse("Success", $data);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = Block::upsert([$request->all()],['block_unique'],['blocker_user_id','blocked_user_id']);
            return $this->successApiResponse("Success", $data);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Block::where('id', $id)->delete();
            return $this->successApiResponse("Success", $data);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }
}
