<?php

namespace App\Http\Controllers;

use App\Http\Requests\Memories\StoreMemoriesRequest;
use App\Http\Requests\Memories\UpdateMemoryRequest;
use App\Models\Photo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class MemoriesController extends Controller
{
    /**
     * Number of photos per page for cursor pagination.
     */
    private const PAGE_SIZE = 24;

    /**
     * Show the user's memories (photo albums) page.
     */
    public function index(Request $request): Response
    {
        $photos = $request->user()->photos()
            ->with('media')
            ->orderByDesc('taken_at')
            ->orderByDesc('id')
            ->cursorPaginate(self::PAGE_SIZE);

        // Capture the cursor BEFORE mapping: `through()` mutates the paginator
        // items in place, which would otherwise build the cursor from our
        // mapped arrays (ISO dates) instead of the raw model attributes.
        $nextCursor = $photos->nextCursor()?->encode();

        /** @var Collection<int, Photo> $photoItems */
        $photoItems = $photos->getCollection();

        $items = $photoItems
            ->map(fn (Photo $photo) => $this->toPhotoArray($photo))
            ->values()
            ->all();

        return Inertia::render('memories/Index', [
            // Merge only when a cursor is supplied so "load more" appends to
            // the already-rendered gallery instead of replacing it.
            'photos' => $request->has('cursor') ? Inertia::merge($items) : $items,
            'next_cursor' => $nextCursor,
        ]);
    }

    /**
     * Upload new photos.
     */
    public function store(StoreMemoriesRequest $request): RedirectResponse
    {
        $this->authorize('create', Photo::class);

        $created = [];

        try {
            foreach ($request->file('images', []) as $file) {
                $dimensions = $this->imageDimensions($file);

                /** @var Photo $photo */
                $photo = $request->user()->photos()->create([
                    'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'taken_at' => $this->capturedAt($file) ?? now(),
                    'width' => $dimensions['width'] ?? null,
                    'height' => $dimensions['height'] ?? null,
                ]);

                $photo->addMedia($file)->toMediaCollection('photos');

                $created[] = $photo;
            }
        } catch (Throwable $e) {
            // All-or-nothing: roll back any photos already stored in this batch
            // so a corrupt file doesn't leave a partial upload behind.
            foreach ($created as $photo) {
                $photo->delete();
            }

            throw $e;
        }

        return back();
    }

    /**
     * Rename a photo or toggle its favorite state.
     */
    public function update(UpdateMemoryRequest $request, Photo $photo): RedirectResponse
    {
        $this->authorize('update', $photo);

        if ($request->filled('title')) {
            $photo->title = $request->input('title');
        }

        if ($request->has('favorite')) {
            $photo->favorited_at = $request->boolean('favorite') ? Carbon::now() : null;
        }

        $photo->save();

        return back();
    }

    /**
     * Delete a photo and its stored files.
     */
    public function destroy(Photo $photo): RedirectResponse
    {
        $this->authorize('delete', $photo);

        $photo->delete();

        return back();
    }

    /**
     * Map a photo to the frontend Photo contract.
     *
     * @return array{id: string, title: string, taken_at: string, src: string, full: string, width: int, height: int, favorite: bool, unviewable: bool}
     */
    private function toPhotoArray(Photo $photo): array
    {
        $media = $photo->getFirstMedia('photos');
        $unviewable = $media === null || str_starts_with($media->mime_type, 'image/heic');

        return [
            'id' => (string) $photo->id,
            'title' => $photo->title ?? 'Untitled',
            'taken_at' => $photo->taken_at !== null
                ? Carbon::parse($photo->taken_at)->toIso8601String()
                : now()->toIso8601String(),
            'src' => $unviewable ? '' : $media->getUrl('thumb'),
            'full' => $unviewable ? '' : $media->getUrl('view'),
            'width' => $photo->width ?? 800,
            'height' => $photo->height ?? 600,
            'favorite' => $photo->favorited_at !== null,
            'unviewable' => $unviewable,
        ];
    }

    /**
     * Read the natural pixel dimensions of an image file.
     *
     * `getimagesize()` reports the raw, pre-rotation dimensions, while the
     * media library's GD driver auto-rotates EXIF-oriented photos when it
     * generates conversions. Store the display-oriented dimensions so the
     * frontend's reserved aspect-ratio stays aligned with the rendered
     * thumbnail — see docs/memories-refresh-layout-spec.md §7 R2.
     *
     * @return array{width: int, height: int}|null
     */
    private function imageDimensions(UploadedFile $file): ?array
    {
        $size = @getimagesize($file->getRealPath());

        if ($size === false) {
            return null;
        }

        $width = $size[0];
        $height = $size[1];

        $orientation = $this->exifOrientation($file);

        // Orientations 5–8 require a quarter-turn, which swaps the axes.
        if (in_array($orientation, [5, 6, 7, 8], true)) {
            [$width, $height] = [$height, $width];
        }

        return ['width' => $width, 'height' => $height];
    }

    /**
     * Read the EXIF orientation tag, when the file provides one.
     */
    private function exifOrientation(UploadedFile $file): ?int
    {
        $orientation = $this->exif($file)['Orientation'] ?? null;

        return $orientation === null ? null : (int) $orientation;
    }

    /**
     * Read the EXIF capture date, when the file provides one.
     */
    private function capturedAt(UploadedFile $file): ?Carbon
    {
        $exif = $this->exif($file);
        $date = $exif['DateTimeOriginal'] ?? $exif['DateTime'] ?? null;

        if (! is_string($date)) {
            return null;
        }

        try {
            return Carbon::parse($date);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Read the file's EXIF data, when the file provides any.
     *
     * @return array<string, mixed>|null
     */
    private function exif(UploadedFile $file): ?array
    {
        if (! function_exists('exif_read_data') || str_starts_with($file->getMimeType(), 'image/heic')) {
            return null;
        }

        $exif = @exif_read_data($file->getRealPath());

        return is_array($exif) ? $exif : null;
    }
}
