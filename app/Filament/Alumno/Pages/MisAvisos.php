<?php

namespace App\Filament\Alumno\Pages;

use App\Models\Announcement;
use BackedEnum;
use Filament\Pages\Page;

class MisAvisos extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $title = 'Avisos';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.mis-avisos';

    public function getViewData(): array
    {
        $alumno = auth()->user()?->alumno;

        if (! $alumno) {
            return ['avisos' => collect()];
        }

        $avisos = Announcement::forStudent($alumno)
            ->orderBy('published_at', 'desc')
            ->get();

        return compact('avisos');
    }
}
