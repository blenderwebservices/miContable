<?php

namespace App\Filament\Resources\TaxpayerResource\Pages;

use App\Filament\Resources\TaxpayerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaxpayers extends ListRecords
{
    protected static string $resource = TaxpayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
