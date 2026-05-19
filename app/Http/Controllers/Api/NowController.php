<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NowPage;
use Illuminate\Http\JsonResponse;

class NowController extends Controller
{
    public function show(): JsonResponse
    {
        $now = NowPage::instance();

        if (! $now->published) {
            return response()->json(['data' => null], 404);
        }

        return response()->json([
            'data' => [
                'location'           => $now->location,
                'status'             => $now->status,
                'focus'              => $now->focus ?? [],
                'reading'            => $now->reading ?? [],
                'learning'           => $now->learning ?? [],
                'vocabulary'         => $now->vocabulary ?? [],
                'content_updated_at' => $now->content_updated_at?->toDateString(),
            ],
        ]);
    }
}
