<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Resources\FichaResource\FichaResource;
use App\Models\Ficha;
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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use UnitEnum;

class FichaSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?string $navigationLabel = 'Config. Fichas';

    protected static ?string $title = 'Configuración de Fichas';

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
                    Section::make('Estado del registro')
                        ->description('Activa o pausa la captación de aspirantes sin perder la configuración.')
                        ->icon(Heroicon::Signal)
                        ->collapsible(false)
                        ->schema([
                            Toggle::make('is_open')
                                ->label('Registro abierto')
                                ->helperText(fn (): HtmlString => new HtmlString(
                                    '<span class="text-xs">Cuando está <b>abierto</b> el botón “Registro de Ficha” aparece en la página principal para aspirantes.</span>'
                                ))
                                ->onColor('success')
                                ->offColor('danger')
                                ->onIcon(Heroicon::OutlinedCheckCircle)
                                ->offIcon(Heroicon::OutlinedXCircle)
                                ->inline(false),
                        ]),

                    Grid::make(2)->schema([
                        Section::make('Ciclo académico')
                            ->description('Periodo al que se asignarán los nuevos folios.')
                            ->icon(Heroicon::OutlinedCalendar)
                            ->schema([
                                Select::make('ciclo_escolar')
                                    ->label('Ciclo escolar')
                                    ->options(fn (): array => $this->getCiclosEscolares())
                                    ->required()
                                    ->native(false)
                                    ->prefixIcon(Heroicon::OutlinedAcademicCap)
                                    ->helperText('Ej. 2025-2026. Se guarda en cada ficha nueva.'),
                            ]),
                        Section::make('Control de folios')
                            ->description('Consecutivo del próximo aspirante. Formato: CBTA254-0000')
                            ->icon(Heroicon::OutlinedHashtag)
                            ->schema([
                                TextInput::make('current_folio')
                                    ->label('Próximo folio')
                                    ->numeric()
                                    ->required()
                                    ->minValue(100)
                                    ->prefix('CBTA254-')
                                    ->helperText(fn (): ?string => $this->getFolioPreview())
                                    ->prefixIcon(Heroicon::OutlinedTicket),
                            ]),
                    ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Guardar cambios')
                                ->icon(Heroicon::OutlinedCheck)
                                ->color('success')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                            Action::make('verFichas')
                                ->label('Ver fichas')
                                ->icon(Heroicon::OutlinedDocumentText)
                                ->color('gray')
                                ->url(fn (): string => FichaResource::getUrl('index'))
                                ->openUrlInNewTab(false),
                        ])->alignEnd(),
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

    public function getFolioPreview(): ?string
    {
        $folio = $this->data['current_folio'] ?? FichaSetting::first()?->current_folio;

        if (blank($folio)) {
            return null;
        }

        return 'Vista previa: CBTA254-'.str_pad((string) $folio, 4, '0', STR_PAD_LEFT);
    }

    public function getStats(): array
    {
        $settings = FichaSetting::first();
        $ciclo = $settings?->ciclo_escolar;
        $total = Ficha::count();
        $delCiclo = $ciclo ? Ficha::where('ciclo_escolar', $ciclo)->count() : 0;

        return [
            'is_open' => (bool) $settings?->is_open,
            'ciclo' => $ciclo ?? '—',
            'current_folio' => $settings?->current_folio ?? '—',
            'next_folio_label' => $settings ? 'CBTA254-'.str_pad((string) $settings->current_folio, 4, '0', STR_PAD_LEFT) : '—',
            'total' => $total,
            'del_ciclo' => $delCiclo,
        ];
    }
}
