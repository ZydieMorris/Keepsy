<?php

namespace App\Models;

use Database\Factories\PhotoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $title
 * @property Carbon|null $taken_at
 * @property Carbon|null $favorited_at
 * @property int|null $width
 * @property int|null $height
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'title', 'taken_at', 'favorited_at', 'width', 'height'])]
class Photo extends Model implements HasMedia
{
    /** @use HasFactory<PhotoFactory> */
    use HasFactory, InteractsWithMedia;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * NOTE: `taken_at` deliberately has NO datetime cast — cursor pagination
     * orders by it, and a Carbon cast would JSON-serialize the cursor point as
     * ISO-8601 ("2025-08-21T23:19:52+00:00"), which string-compares incorrectly
     * against the stored "Y-m-d H:i:s" value and leaks the boundary row into
     * the next page. The raw string orders lexicographically == chronologically.
     */
    protected function casts(): array
    {
        return [
            'favorited_at' => 'datetime',
        ];
    }

    /**
     * The user who owns this photo.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate the thumb + view sizes. HEIC/HEIF cannot be decoded by GD, so
     * those originals are stored as-is without conversions. Originals live on
     * the private `local` disk; conversions render to the public disk under
     * unguessable UUID paths (see UuidPathGenerator).
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        if ($media !== null && str_starts_with($media->mime_type, 'image/heic')) {
            return;
        }

        // nonQueued() first — phpstan resolves the @mixin ImageDriver chain
        // otherwise, treating width()'s return as ImageDriver.
        $this->addMediaConversion('thumb')->nonQueued()->width(600);
        $this->addMediaConversion('view')->nonQueued()->width(1600);
    }
}
