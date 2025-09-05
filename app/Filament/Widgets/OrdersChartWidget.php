<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersChartWidget extends ChartWidget
{
    protected ?string $heading = 'Orders Over Time';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orders->pluck('count')->toArray(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $orders->pluck('date')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('M j');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
