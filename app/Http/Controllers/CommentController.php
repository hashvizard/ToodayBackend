<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
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
            $comments = Comment::create($request->all());
            $response = Comment::with('user:id,profile,name')->where('id', $comments['id'])->get()->toArray();
            Post::where('id', $request->post_id)
                ->increment('comments', 1);
            User::where('id', $request->user_id)
                ->increment('comments', 1);
            DB::commit();
            if (!$response) return $this->errorApiResponse(__('tooday.error'));
            return $this->successApiResponse(__('tooday.userData'), $response);
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
            $comments = Comment::with('user:name,email,profile,id')->where('post_id', $id)->orderBy('id', 'desc')->paginate(15)->toArray();
            if ($comments['next_page_url'] != null) {
                $data = explode('/api/', $comments['next_page_url']);
                $comments['next_page_url'] = $data[1];
            }
            if (!$comments) return $this->errorApiResponse(__('tooday.error'));
            return $this->successApiResponse(__('tooday.userData'), $comments);
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
