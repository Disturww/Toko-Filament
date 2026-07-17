<?php

namespace App\Filament\Widgets;

use App\Models\Cat;
use App\Models\Penjualan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TokoStatsWidget extends BaseWidget
{
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $totalStok = Cat::sum('stok');
        $totalNilaiStok = Cat::sum(\DB::raw('harga * stok'));
        $penjualanBulanIni = Penjualan::whereMonth('tanggal_penjualan', now()->month)
            ->whereYear('tanggal_penjualan', now()->year)
            ->sum('total_harga');

        return [
            Stat::make('Total Stok', number_format($totalStok).' unit')
                ->description('Stok keseluruhan')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('primary'),

            Stat::make('Nilai Inventory', 'Rp'.number_format($totalNilaiStok))
                ->description('Total nilai stok')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('warning'),

            Stat::make('Penjualan Bulan Ini', 'Rp'.number_format($penjualanBulanIni))
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('success'),
        ];
    }
}
