<?php

namespace App\Http\Controllers;

use App\Models\View;
use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
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
            DB::beginTransaction();
            View::create($request->all());
            Post::where('id', $request->post_id)
                ->increment('views', 1);
            User::where('id', $request->user_id)
                ->increment('views', 1);
            DB::commit();
            return $this->successApiPostResponse(__('tooday.cities'));
        } catch (\Exception $e) {
            DB::rollback();
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
            $likedPostsIds = [];
            $likedPosts = View::where('user_id', $id)->orderBy('id', 'desc')->paginate(18, ['id', 'post_id'])->toArray();
            foreach ($likedPosts['data'] as $key => $value) {
                array_push($likedPostsIds, $value['post_id']);
            }
            $posts = Post::whereIn('id', $likedPostsIds)->get(['id', 'photoUrl', 'views', 'location', 'created_at', 'description', 'comments', 'videoUrl'])->toArray();
            if ($likedPosts['next_page_url'] != null) {
                $data = explode('/api/', $likedPosts['next_page_url']);
                $data = ['data' => $posts, 'next_page_url' => $data[1]];
            } else {
                $data = ['data' => $posts, 'next_page_url' => null];
            }
            return $this->successApiPostResponse(__('tooday.cities'), $data);
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
        try {
            $userId = Auth::user()->id;
            $unliked = View::where(['user_id' => $userId, 'post_id' => $id])->delete();
            return $this->successApiPostResponse(__('tooday.cities'), $unliked);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }
}
