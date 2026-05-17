<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'excerpt'      => $this->excerpt,
            'body'         => $this->when($request->routeIs('api.posts.show'), $this->body),
            'cover_image'  => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'status'       => $this->status,
            'featured'     => $this->featured,
            'tags'         => is_array($this->tags) ? $this->tags : [],
            'reading_time' => $this->reading_time,
            'published_at' => $this->published_at?->toISOString(),
            'updated_at'   => $this->updated_at?->toISOString(),
            'author'       => new AuthorResource($this->whenLoaded('author')),
            'categories'   => CategoryResource::collection($this->whenLoaded('categories')),
            'seo' => [
                'title'       => $this->seo_title,
                'description' => $this->seo_description,
                'og_image'    => $this->seo_og_image ? asset('storage/' . $this->seo_og_image) : null,
            ],
        ];
    }
}
