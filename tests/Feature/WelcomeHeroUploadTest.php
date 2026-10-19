<?php

use App\Filament\Admin\Resources\WelcomeContents\Pages\EditWelcomeContent;
use App\Models\User;
use App\Models\WelcomeContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('guarda una imagen en una diapositiva del hero', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $record = WelcomeContent::create([]);

    actingAs($user);

    Livewire::test(EditWelcomeContent::class, ['record' => $record->id])
        ->fillForm([
            'hero_slides' => [
                [
                    'title' => 'Diapositiva de prueba',
                    'image' => [UploadedFile::fake()->image('hero.jpg', 1200, 800)],
                ],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();

    expect($record->hero_slides)->not->toBeNull()
        ->and($record->hero_slides[0]['image'])->toStartWith('welcome/');
});
