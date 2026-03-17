<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ZakatCalculatorController extends Controller
{
    public function zakatCalculator(): View
    {
        return view('kalkulator-zakat.show', [
            'page' => [
                'title' => 'Kalkulator Zakat',
                'description' => 'Bantu hitung estimasi zakat dengan cepat sesuai input data Anda.',
                'view' => 'kalkulator-zakat.pages.zakat',
                'bodyClass' => '',
                'styles' => [],
                'externalStyles' => [],
                'scripts' => [
                    'assets/kalkulator-zakat/zakat.js',
                ],
            ],
        ]);
    }
}
