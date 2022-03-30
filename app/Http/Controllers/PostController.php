<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Traits\ApiResponse;

class PostController extends Controller
{
    Use ApiResponse;
    public function index()
    {
        try {
            $posts = auth()->user()->posts;
            return $this->successApiResponse(__('tooday.cities'), $posts);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e);
        }
    }

    public function show($id)
    {
        try {
            $posts = Post::where('user_id',$id)->orderBy('id', 'desc')->offset(0)->paginate(18,['id','photoUrl','likes','location','created_at','description','comments','videoUrl'])->toArray();
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
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;

        if (auth()->user()->posts()->save($post))
            return response()->json([
                'success' => true,
                'data' => $post->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Post not added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }

        $updated = $post->fill($request->all())->save();

        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Post can not be updated'
            ], 500);
    }

    public function destroy($id)
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }

        if ($post->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be deleted'
            ], 500);
        }
    }
}
