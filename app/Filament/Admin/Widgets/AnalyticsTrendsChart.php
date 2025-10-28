<?php

namespace App\Filament\Admin\Widgets;

use App\Services\Analytics\AnalyticsService;
use Filament\Widgets\ChartWidget;

class AnalyticsTrendsChart extends ChartWidget
{
    protected ?string $heading = 'Aktivitas 30 Hari Terakhir';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $trends = app(AnalyticsService::class)->activityTrends();
        $labels = collect($trends)->pluck('date')->all();

        return [
            'datasets' => [
                [
                    'label' => 'Posts',
                    'data' => collect($trends)->pluck('posts')->all(),
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => '#38bdf822',
                    'tension' => 0.4,
                    'fill' => true,
                ],
                [
                    'label' => 'Comments',
                    'data' => collect($trends)->pluck('comments')->all(),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => '#22c55e22',
                    'tension' => 0.4,
                    'fill' => true,
                ],
                [
                    'label' => 'Users',
                    'data' => collect($trends)->pluck('users')->all(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => '#f59e0b22',
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
