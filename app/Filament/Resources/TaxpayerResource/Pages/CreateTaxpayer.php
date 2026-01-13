<?php

namespace App\Filament\Resources\TaxpayerResource\Pages;

use App\Filament\Resources\TaxpayerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTaxpayer extends CreateRecord
{
    protected static string $resource = TaxpayerResource::class;
}
