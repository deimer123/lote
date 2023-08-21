<?php

namespace App\Filament\Widgets\Dashboard;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Venta;
use App\Models\User;
use App\Models\Lote;

class UserCount extends BaseWidget
{

    protected static ?string $pollingInterval = null;

    protected function getCards(): array
    {
        return [
            Card::make('Numero Clientes ', User::count())
            ->icon('heroicon-o-user')
            ->descriptionIcon('heroicon-s-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
            Card::make('Numero Lotes ', Lote::count())
            ->icon('heroicon-o-academic-cap')
            ->descriptionIcon('heroicon-s-trending-up')
            ->chart([7, 2, 5, 3, 8, 4, 20])
            ->color('success'),
            Card::make('Numero Ventas ', Venta::count())
            ->icon('heroicon-o-presentation-chart-line')
            ->descriptionIcon('heroicon-s-trending-up')
            ->chart([7, 2, 10, 3, 2, 4, 1])
            ->color('success'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }



}
