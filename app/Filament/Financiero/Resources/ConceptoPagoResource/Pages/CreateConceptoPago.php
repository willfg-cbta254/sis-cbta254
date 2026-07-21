<?php

namespace App\Filament\Financiero\Resources\ConceptoPagoResource\Pages;

use App\Filament\Financiero\Resources\ConceptoPagoResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;
use Override;

class CreateConceptoPago extends CreateRecord
{
    protected static string $resource = ConceptoPagoResource::class;

    #[Override]
    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Concepto de pago guardado!!';
    }

    #[Override]
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Concepto Creado')
            ->body('El concepto de pago ha sido creado exitosamente.')
            ->success()
            ->icon(Heroicon::CurrencyDollar);
    }
}
