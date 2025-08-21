<?php

namespace App\Action\CRM\Quotation;

use App\Models\Quotation;

class GenerateQuotationNumber
{
    public static function handle(string $categoryCode): string
    {
        $today = now();
        $year = 2025; // fix tahun sesuai requirement
        $monthRoman = self::toRoman($today->month);
        $day = $today->format('d');

        // Ambil nomor urut terakhir per tahun
        $last = Quotation::whereYear('created_at', $year)->latest()->first();
        $number = $last ? intval(substr($last->quotation_number, 4, 3)) + 1 : 1;
        $numberFormatted = str_pad($number, 3, '0', STR_PAD_LEFT);

        return "BOQ-{$numberFormatted}/{$day}/{$categoryCode}/JLC/{$monthRoman}/{$year}";
    }

    private static function toRoman($month): string
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        return $map[$month];
    }
}
