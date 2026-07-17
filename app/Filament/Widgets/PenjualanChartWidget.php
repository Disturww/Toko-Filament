<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use Filament\Widgets\ChartWidget;

class PenjualanChartWidget extends ChartWidget
{
    protected ?string $pollingInterval = '15s';

    protected ?string $heading = 'Grafik Penjualan';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = '1';

    protected ?string $maxHeight = '200px';

    protected function getData(): array
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i));
        }

        $data = $months->map(function ($month) {
            return Penjualan::whereMonth('tanggal_penjualan', $month->month)
                ->whereYear('tanggal_penjualan', $month->year)
                ->sum('total_harga');
        });

        $labels = $months->map(fn ($m) => $m->translatedFormat('M'));

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan',
                    'data' => $data->toArray(),
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
