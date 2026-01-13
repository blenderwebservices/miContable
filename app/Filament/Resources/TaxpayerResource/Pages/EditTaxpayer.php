<?php

namespace App\Filament\Resources\TaxpayerResource\Pages;

use App\Filament\Resources\TaxpayerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaxpayer extends EditRecord
{
    protected static string $resource = TaxpayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
