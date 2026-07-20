<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $query = Post::with(['user', 'categories']);

        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        if (request('category')) {
            $query->whereHas('categories', function ($q) {
                $q->where('categories.id', request('category'));
            });
        }

        $allowedSorts = ['title', 'created_at'];

        $sort = request('sort', 'created_at');
        $order = request('order', 'desc');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $posts = $query->orderBy($sort, $order)->paginate(5);

        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $data['user_id'] = $request->user()->id;

        $post = Post::create($data);

        if (isset($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }

        $post->load(['user', 'categories']);

        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'data' => new PostResource($post),
        ], 201);
    }

    public function show(Post $post)
    {
        $post->load(['user', 'categories']);

        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }

            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        if (array_key_exists('categories', $data)) {
            $post->categories()->sync($data['categories']);
        }

        $post->load(['user', 'categories']);

        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully',
            'data' => new PostResource($post),
        ]);
    }

    public function destroy(Post $post)
    {
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->categories()->detach();
        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully',
        ]);
    }
}