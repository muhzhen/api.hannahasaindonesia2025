<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    //
    public function index()
    {
        // Mengambil data postingan yang dipublikasikan, diurutkan berdasarkan tanggal, dan dipaginasi
        return PostResource::collection(
            Post::where('is_published', true)
                ->orderBy('tanggal', 'desc')
                ->paginate(5)
        );
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        // Pastikan query tidak kosong
        if (!$query) {
            return response()->json(['error' => 'Search query is required'], 400);
        }

        // Lakukan pencarian di 'title' atau 'content'
        $results = PostResource::collection(Post::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->get());

        // Jika tidak ada hasil
        if ($results->isEmpty()) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'No posts found'
            ], 404);
        }

        // Return hasil pencarian
        return response()->json([
            'statusCode' => 200,
            'message' => 'success',
            'data' => $results
        ]);
    }

    public function show($slug)
    {
        // Cari data berdasarkan slug
        $post = Post::where('slug', $slug)->first();

        // Jika tidak ada data, kembalikan response 404
        if (!$post) {
            return response()->json(['statusCode' => 404, 'message' => 'Post not found'], 404);
        }

        // Kembalikan data post dalam format resource
        return new PostResource($post);
    }


}
