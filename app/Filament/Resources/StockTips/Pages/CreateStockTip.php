<?php

namespace App\Filament\Resources\StockTips\Pages;

use App\Filament\Resources\StockTips\StockTipResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockTip extends CreateRecord
{
    protected static string $resource = StockTipResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
