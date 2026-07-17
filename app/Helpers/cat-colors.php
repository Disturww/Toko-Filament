<?php

if (! function_exists('getCatColorMap')) {
    function getCatColorMap(): array
    {
        return [
            'merah' => ['bg' => '#FEE2E2', 'text' => '#DC2626', 'accent' => '#EF4444'],
            'biru' => ['bg' => '#DBEAFE', 'text' => '#2563EB', 'accent' => '#3B82F6'],
            'hijau' => ['bg' => '#D1FAE5', 'text' => '#059669', 'accent' => '#10B981'],
            'kuning' => ['bg' => '#FEF3C7', 'text' => '#D97706', 'accent' => '#F59E0B'],
            'putih' => ['bg' => '#F9FAFB', 'text' => '#6B7280', 'accent' => '#9CA3AF'],
            'hitam' => ['bg' => '#1F2937', 'text' => '#F9FAFB', 'accent' => '#4B5563'],
            'abu-abu' => ['bg' => '#E5E7EB', 'text' => '#4B5563', 'accent' => '#6B7280'],
            'coklat' => ['bg' => '#D4A574', 'text' => '#FFFFFF', 'accent' => '#B8860B'],
            'krem' => ['bg' => '#FEF7ED', 'text' => '#92400E', 'accent' => '#D97706'],
            'orange' => ['bg' => '#FFEDD5', 'text' => '#EA580C', 'accent' => '#F97316'],
            'ungu' => ['bg' => '#F3E8FF', 'text' => '#9333EA', 'accent' => '#A855F7'],
        ];
    }
}

if (! function_exists('getCatSwatchColors')) {
    function getCatSwatchColors(?string $warna): array
    {
        $colorMap = getCatColorMap();
        $key = strtolower($warna ?? '');

        return $colorMap[$key] ?? ['bg' => '#FEF3C7', 'text' => '#92400E', 'accent' => '#D97706'];
    }
}
