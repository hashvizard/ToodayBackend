<?php
namespace App\Http\Controllers;


use App\Models\Block;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use App\Models\View;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
                ['reported','<',5],
                ['status','=',0]
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

    // Showing all posts views [Users]
    public function postViewUsers($id)
    {
        try {
            $viewPeople = View::where('post_id',$id)->orderBy('id', 'ASC')->paginate(12,'user_id');
            $viewPeopleAllData = $viewPeople->toArray();
            $userIdArray = $viewPeople->pluck('user_id');
            if ($viewPeopleAllData['next_page_url'] != null) {

                $data = explode('/api/', $viewPeopleAllData['next_page_url']);
                $viewPeopleAllData['next_page_url'] = $data[1];
            }
            $viewPeopleAllData['data'] = $userIdArray->toArray();

            $users = User::whereIn('id',$viewPeopleAllData['data'])->get()->toArray();
            $viewPeopleAllData['data'] = $users;
            return $this->successApiResponse('success',$viewPeopleAllData);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }


    // Showing all posts to admin
    public function adminPosts()
    {
        try {
            $posts = Post::with('user:id,name,profile,bio,profession,views,posts,reviews,comments')->where([
                ['status','=',1]
                ])->orderBy('id', 'ASC')->paginate(12)->toArray();

            if ($posts['next_page_url'] != null) {
                $data = explode('/api/', $posts['next_page_url']);
                $posts['next_page_url'] = $data[1];
            }
            return $this->successApiResponse('success', $posts);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e->getMessage());
        }
    }

    public function userPosts($id){
        try {
            $reportedPosts = Report::where('user_id',$id)->pluck('post_id');
            $posts = Post::with('user:id,name,profile,bio,profession,views,posts,reviews,comments')->whereNotIn('id',$reportedPosts)->where([
                ['reported','<',5],
                ['user_id','=',$id],
                ['status','=',0]
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

            $thumbnailPath =$request->file(key:'photoUrl')->store(path:'thumbnail',options:'s3');
            Storage::disk(name:'s3')->setVisibility($thumbnailPath,visibility:'public');

            $post = Post::create([
                'user_id'=>$user->id,
                'city_id'=>$user->city_id,
                'videoUrl'=>$request->videoUrl,
                'photoUrl'=>Storage::disk(name:'s3')->url($thumbnailPath),
                'location'=>$request->location,
                'description'=>$request->description
            ]);

            $user->posts = $user->posts + 1;
            $user->save();

            // Checking whether limit has exceeded or not for city
            $totalPosts = Post::where([
                ['city_id','=',$user->city_id],
                ['status','=',0]
                ])->count();
            $cityLimit  = City::where('id',$user->city_id)->get('limit')->toArray();

            if($totalPosts > $cityLimit[0]['limit']){
                $removePosts = $totalPosts - $cityLimit[0]['limit'];

                $data = Post::where('city_id',$user->city_id)->orderBy('id', 'ASC')->limit($removePosts)->get()->toArray(); // the oldest entry

                foreach ($data as $key => $value) {
                    $post = Post::where('id',$value['id'])->get(['videoUrl','photoUrl'])->toArray();
                    $videoUrl = str_replace('https://tooday.s3.amazonaws.com/','',$post[0]['videoUrl']);
                    $videoUrl = str_replace('%2F','/',$videoUrl);

                    $photoUrl = str_replace('https://tooday.s3.ap-south-1.amazonaws.com/','',$post[0]['photoUrl']);

                    // Deleting video and thumbnail from s3
                    Storage::disk(name:'s3')->delete($videoUrl);
                    Storage::disk(name:'s3')->delete($photoUrl);

                    Post::where('id',$value['id'])->delete();
                }

            }

            return $this->successApiPostResponse(__('success'));

        } catch (\Exception $e) {
            return $this->errorApiResponse($e->getMessage());
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


    // Set video to live
    public function setlive($id)
    {
        try {
            $posts = Post::where('id',$id)->update([
                'status' => 0
             ]);
            return $this->successApiPostResponse(__('tooday.cities'), $posts);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $post = Post::where('id',$id)->get(['videoUrl','photoUrl'])->toArray();
            $videoUrl = str_replace('https://tooday.s3.amazonaws.com/','',$post[0]['videoUrl']);
            $videoUrl = str_replace('%2F','/',$videoUrl);

            $photoUrl = str_replace('https://tooday.s3.ap-south-1.amazonaws.com/','',$post[0]['photoUrl']);

            // Deleting video and thumbnail from s3
            Storage::disk(name:'s3')->delete($videoUrl);
            Storage::disk(name:'s3')->delete($photoUrl);

            Post::where('id',$id)->delete();
            DB::commit();
            return $this->successApiPostResponse(__('success'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorApiResponse($e->getMessage());
        }
    }
}
