<?php

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a blog post with the factory', function () {
    $post = BlogPost::factory()->create();

    expect($post)->toBeInstanceOf(BlogPost::class)
        ->and($post->title)->not->toBeEmpty()
        ->and($post->slug)->not->toBeEmpty()
        ->and($post->author)->toBeInstanceOf(User::class);
});

it('auto-generates slug from title', function () {
    $post = BlogPost::factory()->create(['slug' => '']);

    $expectedSlug = str($post->title)->slug()->toString();
    expect($post->slug)->toBe($expectedSlug);
});

it('can create a published post', function () {
    $post = BlogPost::factory()->published()->create();

    expect($post->is_published)->toBeTrue()
        ->and($post->published_at)->not->toBeNull();
});

it('can create an unpublished post', function () {
    $post = BlogPost::factory()->create();

    expect($post->is_published)->toBeFalse()
        ->and($post->published_at)->toBeNull();
});

it('belongs to an author', function () {
    $post = BlogPost::factory()->create();

    expect($post->author)->toBeInstanceOf(User::class);
});

it('has correct fillable attributes', function () {
    $post = BlogPost::factory()->create([
        'title' => 'Test Title',
        'content' => '<p>Hello</p>',
        'excerpt' => 'Short excerpt',
    ]);

    expect($post->title)->toBe('Test Title')
        ->and($post->content)->toBe('<p>Hello</p>')
        ->and($post->excerpt)->toBe('Short excerpt');
});
