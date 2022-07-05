<?php
namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Report;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    Use ApiResponse;

    public function index()
    {
        try {
            $City_id = Auth::user()->city_id;
            $reportedPosts = Report::where('user_id',Auth::user()->id)->pluck('post_id');

            $blockedusers = Block::where('blocker_user_id',Auth::user()->id)->pluck('blocked_user_id');

            $posts = Post::with('user:id,name,profile,bio,profession,views,posts,reviews,comments')->whereNotIn('user_id',$blockedusers)->whereNotIn('id',$reportedPosts)->where([
                ['city_id','=',$City_id],
                ['reported','<',5]
                ])->orderBy('id', 'DESC')->paginate(12)->toArray();

            shuffle($posts['data']);

            if ($posts['next_page_url'] != null) {
                $data = explode('/api/', $posts['next_page_url']);
                $posts['next_page_url'] = $data[1];
            }
            return $this->successApiResponse(__('tooday.cities'), $posts);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }

    public function userPosts($id){
        try {
            $reportedPosts = Report::where('user_id',$id)->pluck('post_id');
            $posts = Post::with('user:id,name,profile,bio,profession,views,posts,reviews,comments')->whereNotIn('id',$reportedPosts)->where([
                ['reported','<',5],
                ['user_id','=',$id]
                ])->orderBy('id', 'desc')->paginate(12)->toArray();

            if ($posts['next_page_url'] != null) {
                $data = explode('/api/', $posts['next_page_url']);
                $posts['next_page_url'] = $data[1];
            }
            return $this->successApiResponse(__('tooday.cities'), $posts);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }

    public function show($id)
    {
        try {
            $posts = Post::where('user_id',$id)->orderBy('id', 'desc')->paginate(18,['id','photoUrl','views','location','created_at','description','comments','videoUrl'])->toArray();
            if ($posts['next_page_url'] != null) {
                $data = explode('/api/', $posts['next_page_url']);
                $posts['next_page_url'] = $data[1];
            }

            return $this->successApiPostResponse(__('tooday.cities'), $posts);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }


    public function store(Request $request)
    {

        try {
            $user=Auth::user();

            $videoPath =$request->file(key:'videoUrl')->store(path:'videos/'.$user->city_id.'/'.$user->uid,options:'s3');
            Storage::disk(name:'s3')->setVisibility($videoPath,visibility:'public');
            $thumbnailPath =$request->file(key:'photoUrl')->store(path:'videos/'.$user->city_id.'/'.$user->uid,options:'s3');
            Storage::disk(name:'s3')->setVisibility($thumbnailPath,visibility:'public');

            $post = Post::create([
                'user_id'=>$user->id,
                'city_id'=>$user->city_id,
                'videoUrl'=>Storage::disk(name:'s3')->url($videoPath),
                'photoUrl'=>Storage::disk(name:'s3')->url($thumbnailPath),
                'location'=>$request->location,
                'description'=>$request->description
            ]);
            $user->posts = $user->posts + 1;
            $user->save();
            return $post;
            return $this->successApiPostResponse(__('tooday.cities'), $post);
        } catch (\Exception $e) {
            return $e;
            return $this->errorApiResponse($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $posts = Post::where('id',$id)->update($request->all());
            return $this->successApiPostResponse(__('tooday.cities'), $posts);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }

    public function destroy($id)
    {
        try {
            $posts = Post::where('id',$id)->delete();
            return $this->successApiPostResponse(__('tooday.cities'), $posts);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }
}
