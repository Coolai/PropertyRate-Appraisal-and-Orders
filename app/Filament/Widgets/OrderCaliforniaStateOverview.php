<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderCaliforniaStateOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('California Orders', Order::query()->where('state_code', 'CA')->count()),
        ];
    }
}
