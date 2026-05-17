<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Post::published()
            ->with(['author', 'categories'])
            ->orderByDesc('published_at');

        if ($request->filled('category')) {
            $query->whereHas('categories', fn ($q) =>
                $q->where('slug', $request->category)
            );
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn ($builder) =>
                $builder->where('title', 'ilike', "%$q%")
                        ->orWhere('excerpt', 'ilike', "%$q%")
            );
        }

        if ($request->boolean('featured')) {
            $query->featured();
        }

        $limit = min((int) $request->get('limit', 20), 100);

        return PostResource::collection(
            $request->filled('limit') ? $query->take($limit)->get() : $query->paginate(12)
        );
    }

    public function show(string $slug): PostResource
    {
        $post = Post::published()
            ->with(['author', 'categories'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new PostResource($post);
    }

    public function slugs(): \Illuminate\Http\JsonResponse
    {
        $slugs = Post::published()->pluck('slug');
        return response()->json($slugs);
    }

    public function related(Request $request, string $slug): AnonymousResourceCollection
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();
        $categoryIds = $post->categories()->pluck('categories.id');

        $related = Post::published()
            ->with(['author', 'categories'])
            ->where('id', '!=', $post->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds))
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return PostResource::collection($related);
    }
}
