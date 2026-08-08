<?php

use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to the login page', function () {
    $this->get(route('memories'))
        ->assertRedirect(route('login'));
});

test('authenticated users can visit the memories page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('memories'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('memories/Index')
            ->has('photos', 0)
            ->where('next_cursor', null));
});

test('the memories page only lists the current user\'s photos', function () {
    $user = User::factory()->create();
    $photo = Photo::factory()->for($user)->create(['taken_at' => now()]);
    Photo::factory()->for(User::factory()->create())->create();

    $this->actingAs($user)
        ->get(route('memories'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('memories/Index')
            ->has('photos', 1)
            ->where('photos.0.id', (string) $photo->id)
            ->where('photos.0.title', $photo->title)
            ->where('photos.0.favorite', false)
            ->where('photos.0.unviewable', true));
});

test('photos are cursor paginated at 24 per page', function () {
    $user = User::factory()->create();
    Photo::factory()->count(25)->for($user)->create();

    $first = $this->actingAs($user)->get(route('memories'));

    $first->assertInertia(fn (Assert $page) => $page
        ->has('photos', 24)
        ->where('next_cursor', fn (string $cursor) => $cursor !== ''));

    $cursor = $first->viewData('page')['props']['next_cursor'];

    $this->actingAs($user)
        ->get(route('memories', ['cursor' => $cursor]))
        ->assertInertia(fn (Assert $page) => $page
            ->has('photos', 1)
            ->where('next_cursor', null));
});

test('authenticated users can upload photos', function () {
    $user = User::factory()->create();

    $this->from(route('memories'))
        ->actingAs($user)
        ->post(route('memories.store'), [
            'images' => [UploadedFile::fake()->image('beach.jpg', 800, 600)],
        ])
        ->assertRedirect(route('memories'));

    $this->assertDatabaseHas('photos', [
        'user_id' => $user->id,
        'title' => 'beach',
    ]);

    $photo = $user->photos()->first();
    $media = $photo->getFirstMedia('photos');

    expect($photo->width)->not->toBeNull();
    expect($photo->height)->not->toBeNull();
    expect($photo->taken_at)->not->toBeNull();
    expect($media)->not->toBeNull();
    expect($media->hasGeneratedConversion('thumb'))->toBeTrue();
    expect($media->hasGeneratedConversion('view'))->toBeTrue();
});

test('uploads reject non-image files', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('memories.store'), [
            'images' => [UploadedFile::fake()->create('notes.txt')],
        ])
        ->assertSessionHasErrors('images.0');

    expect($user->photos()->count())->toBe(0);
});

test('uploads enforce a maximum of 20 files', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('memories.store'), [
            'images' => collect(range(1, 21))
                ->map(fn () => UploadedFile::fake()->image('photo.jpg'))
                ->all(),
        ])
        ->assertSessionHasErrors('images');

    expect($user->photos()->count())->toBe(0);
});

test('heic files are stored original-only without conversions', function () {
    $photo = Photo::factory()->create();

    // Minimal HEIC signature (ftyp box) so fileinfo detects image/heic.
    $file = UploadedFile::fake()->createWithContent(
        'photo.heic',
        hex2bin('000000186674797068656963000000006d69663832'),
    );

    $media = $photo->addMedia($file)->toMediaCollection('photos');

    expect($media->mime_type)->toBe('image/heic');
    expect($media->hasGeneratedConversion('thumb'))->toBeFalse();
    expect($media->hasGeneratedConversion('view'))->toBeFalse();
});

test('photo title can be renamed', function () {
    $user = User::factory()->create();
    $photo = Photo::factory()->for($user)->create();

    $this->actingAs($user)
        ->patch(route('memories.update', $photo), ['title' => 'New Title'])
        ->assertRedirect();

    expect($photo->fresh()->title)->toBe('New Title');
});

test('photo favorite state can be toggled', function () {
    $user = User::factory()->create();
    $photo = Photo::factory()->for($user)->create();

    $this->actingAs($user)
        ->patch(route('memories.update', $photo), ['favorite' => true])
        ->assertRedirect();

    expect($photo->fresh()->favorited_at)->not->toBeNull();

    $this->actingAs($user)
        ->patch(route('memories.update', $photo), ['favorite' => false])
        ->assertRedirect();

    expect($photo->fresh()->favorited_at)->toBeNull();
});

test('users cannot update another user\'s photo', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $photo = Photo::factory()->for($owner)->create();

    $this->actingAs($intruder)
        ->patch(route('memories.update', $photo), ['title' => 'Hacked'])
        ->assertForbidden();

    expect($photo->fresh()->title)->not->toBe('Hacked');
});

test('media files are stored under unguessable uuid paths', function () {
    $user = User::factory()->create();

    $this->from(route('memories'))
        ->actingAs($user)
        ->post(route('memories.store'), [
            'images' => [UploadedFile::fake()->image('private.jpg', 800, 600)],
        ])
        ->assertRedirect(route('memories'));

    $media = $user->photos()->first()->getFirstMedia('photos');

    expect($media->uuid)->not->toBeNull();
    expect($media->getPath())->toContain($media->uuid);
    expect($media->getUrl())->toContain($media->uuid);
    expect($media->getUrl())->not->toContain('/media/'.$media->model_id.'/');
});

test('photo can be deleted and removes its stored files', function () {
    Storage::fake('local');
    Storage::fake('public');

    $user = User::factory()->create();
    $photo = Photo::factory()->for($user)->create();
    $media = $photo
        ->addMedia(UploadedFile::fake()->image('pic.jpg', 400, 300))
        ->toMediaCollection('photos');

    $originalPath = $media->getPath();
    $conversionPath = $media->getPath('thumb');

    $this->actingAs($user)
        ->delete(route('memories.destroy', $photo))
        ->assertRedirect();

    expect(Photo::find($photo->id))->toBeNull();
    Storage::disk('local')->assertMissing($originalPath);
    Storage::disk('public')->assertMissing($conversionPath);
});

test('users cannot delete another user\'s photo', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $photo = Photo::factory()->for($owner)->create();

    $this->actingAs($intruder)
        ->delete(route('memories.destroy', $photo))
        ->assertForbidden();

    expect(Photo::find($photo->id))->not->toBeNull();
});
