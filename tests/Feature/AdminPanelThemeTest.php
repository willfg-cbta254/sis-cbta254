<?php

use Filament\Facades\Filament;

it('uses a smaller brand logo on the admin panel so the top menu fits on one row', function () {
    expect(Filament::getPanel('admin')->getBrandLogoHeight())->toBe('2rem');
});

it('keeps the original gradient topbar and only restyles the menu in dark mode', function () {
    $theme = file_get_contents(resource_path('css/filament/admin/theme.css'));

    expect($theme)
        ->toContain('linear-gradient')
        ->toContain('.fi-topbar-item')
        ->toContain('.dark .fi-topbar-item')
        ->toContain('.dark .fi-topbar-item-label')
        ->toContain('lg:flex-nowrap')
        ->not->toContain('.dark .fi-topbar {')
        ->not->toContain('fi-sidebar-item-label');
});
