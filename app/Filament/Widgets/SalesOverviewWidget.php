<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalProducts = Product::where('is_active', true)->count();
        $totalUsers = User::count();

        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $yesterdayOrders = Order::whereDate('created_at', today()->subDay())->count();
        $yesterdayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today()->subDay())
            ->sum('total_amount');

        $ordersGrowth = $yesterdayOrders > 0 ? (($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100 : 0;
        $revenueGrowth = $yesterdayRevenue > 0 ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100 : 0;

        return [
            Stat::make('Total Orders', number_format($totalOrders))
                ->description('All time orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('All time revenue')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Active Products', number_format($totalProducts))
                ->description('Products in catalog')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),

            Stat::make('Total Users', number_format($totalUsers))
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),

            Stat::make('Today\'s Orders', number_format($todayOrders))
                ->description($ordersGrowth >= 0 ? '+' . number_format($ordersGrowth, 1) . '% from yesterday' : number_format($ordersGrowth, 1) . '% from yesterday')
                ->descriptionIcon($ordersGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($ordersGrowth >= 0 ? 'success' : 'danger'),

            Stat::make('Today\'s Revenue', '$' . number_format($todayRevenue, 2))
                ->description($revenueGrowth >= 0 ? '+' . number_format($revenueGrowth, 1) . '% from yesterday' : number_format($revenueGrowth, 1) . '% from yesterday')
                ->descriptionIcon($revenueGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueGrowth >= 0 ? 'success' : 'danger'),
        ];
    }
}
