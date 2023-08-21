<?php

namespace App\Filament\Widgets\Dashboard;

use Filament\Widgets\BarChartWidget;
use App\Models\Venta;
use Carbon\Carbon;

class VentasChart extends BarChartWidget
{
    protected static ?string $heading = 'Ventas';

    protected function getData(): array
    {
        $ventas = Venta::select('created_at')->get()->groupBy(function($ventas){
            return Carbon::parse($ventas->created_at)->format('F');
        });
        $quantities = [];
        foreach ($ventas as $venta => $value) {
            array_push($quantities, $value->count());
        }
        return[
            'datasets'=>[
                [
                    'label' => 'Ventas Mensuales',
                    'data' => $quantities,
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(154, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    'borderColor'=> [
                        'rgb(255, 99, 132',
                        'rgb(255, 159, 64',
                        'rgb(255, 205, 86',
                        'rgb(255, 192, 192',
                        'rgb(255, 162, 235',
                        'rgb(255, 102, 255',
                        'rgb(255, 203, 207'
                    ],
                    'borderWidth' => 1
                ],
            ],
            'labels' => $ventas->keys(),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }
}
