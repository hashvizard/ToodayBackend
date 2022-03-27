<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $review = Review::create($request->all());

            $response = Review::with('users:id,profile,name')->where('id', $review['id'])->get()->toArray();


            if (!$response) return $this->errorApiResponse(__('tooday.error'));
            return $this->successApiResponse("Success", $response);
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
        try {
            $reviews = Review::with('users:id,profile,name')->where('profile_user_id', $id)->orderBy('id', 'desc')->paginate(15)->toArray();
            if ($reviews['next_page_url'] != null) {
                $data = explode('/api/', $reviews['next_page_url']);
                $reviews['next_page_url'] = $data[1];
            }

            if (!$reviews) return $this->errorApiResponse(__('tooday.error'));
            return $this->successApiResponse("Success", $reviews);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
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
        //
    }
}
