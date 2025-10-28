<?php

namespace App\Filament\Admin\Widgets;

use App\Services\Doctor\DoctorService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class DoctorLatencyChart extends ChartWidget
{
    protected ?string $heading = 'Respon Komponen (ms)';

    protected ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $report = app(DoctorService::class)->run();
        $labels = [];
        $data = [];

        foreach ($report['checks'] ?? [] as $key => $check) {
            $labels[] = Str::headline((string) $key);
            $data[] = (float) ($check['latency_ms'] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Latency (ms)',
                    'data' => $data,
                    'backgroundColor' => '#38bdf8',
                    'borderRadius' => 10,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
