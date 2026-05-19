<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NowPage extends Model
{
    protected $table = 'now_page';

    protected $fillable = [
        'location',
        'status',
        'focus',
        'reading',
        'learning',
        'vocabulary',
        'published',
        'content_updated_at',
    ];

    protected $casts = [
        'focus'              => 'array',
        'reading'            => 'array',
        'learning'           => 'array',
        'vocabulary'         => 'array',
        'published'          => 'boolean',
        'content_updated_at' => 'date',
    ];

    /**
     * Always work with a single record (singleton pattern).
     */
    public static function instance(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'location'           => 'Tokyo, Japan',
                'published'          => true,
                'content_updated_at' => now(),
            ]
        );
    }
}
