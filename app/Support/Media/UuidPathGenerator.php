<?php

namespace App\Support\Media;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Stores media under an unguessable directory derived from the media UUID.
 *
 * Conversions live on the public disk, so the default generator's predictable
 * `{media_id}/{collection}/{filename}` layout would let anyone enumerate other
 * users' photos. Deriving the path from the media UUID keeps conversion URLs
 * unguessable without signing.
 */
class UuidPathGenerator implements PathGenerator
{
    /**
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /**
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    /**
     * Build the uuid-sharded base path for a media item.
     */
    protected function getBasePath(Media $media): string
    {
        $uuid = (string) $media->uuid;

        return $uuid !== '' ? substr($uuid, 0, 2).'/'.$uuid : (string) $media->id;
    }
}
