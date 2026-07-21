<?php

namespace App\Filament\Admin\Pages;

use App\Models\FichaSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use UnitEnum;

class FichaSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?string $navigationLabel = 'Config. Fichas';

    protected static ?string $title = 'Configuración de Fichas de Nuevo Ingreso';

    protected ?string $subheading = 'Controla el registro de fichas de nuevo ingreso para aspirantes.';

    public ?array $data = [];

    protected ?string $pollingInterval = '5s';

    public function getView(): string
    {
        return 'filament.admin.pages.ficha-settings';
    }

    public function mount(): void
    {
        $settings = FichaSetting::first();

        if (! $settings) {
            $settings = FichaSetting::create([
                'is_open' => false,
                'ciclo_escolar' => date('Y').'-'.(date('Y') + 1),
                'current_folio' => 100,
            ]);
        }

        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Toggle::make('is_open')
                        ->label('Registro abierto')
                        ->helperText('Activa o desactiva el botón de registro de fichas en la página principal'),
                    Select::make('ciclo_escolar')
                        ->label('Ciclo Escolar')
                        ->options(fn () => $this->getCiclosEscolares())
                        ->required(),
                    TextInput::make('current_folio')
                        ->label('Folio actual')
                        ->numeric()
                        ->required()
                        ->minValue(100),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Guardar')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $settings = FichaSetting::first();
        $settings->update($this->form->getState());

        Notification::make()
            ->title('Configuración guardada correctamente.')
            ->success()
            ->send();
    }

    protected function getCiclosEscolares(): array
    {
        $year = (int) date('Y');
        $options = [];

        for ($i = -1; $i <= 2; $i++) {
            $start = $year + $i;
            $end = $start + 1;
            $options["{$start}-{$end}"] = "{$start}-{$end}";
        }

        return $options;
    }
}
