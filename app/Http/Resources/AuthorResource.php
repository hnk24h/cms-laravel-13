<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'slug'    => $this->slug,
            'avatar'  => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'bio'     => $this->bio,
            'twitter' => $this->twitter,
            'github'  => $this->github,
            'website' => $this->website,
        ];
    }
}
