<?php

namespace App\Filament\Financiero\Resources\PagoResource\Pages;

use App\Filament\Financiero\Resources\PagoResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPago extends EditRecord
{
    protected static string $resource = PagoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimir_recibo')
                ->label('Imprimir Recibo')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn () => route('pagos.recibo', $this->record))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
