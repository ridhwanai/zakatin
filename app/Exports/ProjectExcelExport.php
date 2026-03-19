<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProjectExcelExport implements FromArray, WithColumnWidths, WithEvents
{
    private const TITLE_ROW = 1;
    private const SUMMARY_TITLE_ROW = 4;
    private const SUMMARY_START_ROW = 5;
    private const SUMMARY_END_ROW = 10;
    private const TABLE_TITLE_ROW = 12;
    private const TABLE_HEADING_ROW = 13;
    private const TABLE_START_ROW = 14;

    private int $lastRow = self::TABLE_START_ROW;

    public function __construct(
        private readonly Project $project,
        private readonly array $summary,
        private readonly string $exportedAt
    ) {
    }

    public function array(): array
    {
        $rows = [
            ["REKAP ZAKAT - {$this->project->title}"],
            ['Tanggal Export', $this->exportedAt],
            [''],
            ['RINGKASAN'],
            ['Total Pendaftar', (int) $this->summary['total_list_count']],
            ['Total Muzakki', (int) $this->summary['total_people']],
            ['Total Fitrah Beras (Kg)', (float) $this->summary['total_rice_kg']],
            ['Total Fitrah Uang (Rp)', (float) $this->summary['total_fitrah_money']],
            ['Uang Wajib / Infaq (Rp)', (float) $this->summary['total_wajib_money']],
            ['Total Zakat Mal (Rp)', (float) $this->summary['total_mal_money']],
            [''],
            ['DAFTAR ORANG SUDAH ZAKAT'],
            [
            'NO',
            'NAMA',
            'ORANG',
            'METODE',
            'FITRAH BERAS (KG)',
            'FITRAH UANG (RP)',
            'WAJIB / INFAQ (RP)',
            'ZAKAT MAL (RP)',
            ],
        ];

        foreach ($this->project->zakatRecords as $index => $record) {
            $rows[] = [
                $index + 1,
                $record->name,
                (int) $record->people_count,
                $record->method === 'rice' ? 'Beras' : 'Tunai',
                $record->rice_kg !== null ? (float) $record->rice_kg : null,
                $record->fitrah_money !== null ? (float) $record->fitrah_money : null,
                (float) $record->wajib_money,
                (float) $record->mal_money,
            ];
        }

        if ($this->project->zakatRecords->isEmpty()) {
            $rows[] = ['-', 'Belum ada data orang zakat.', '-', '-', '-', '-', '-', '-'];
        }

        $this->lastRow = count($rows);

        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 28,
            'C' => 10,
            'D' => 12,
            'E' => 18,
            'F' => 18,
            'G' => 20,
            'H' => 18,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A'.self::TITLE_ROW.':H'.self::TITLE_ROW);
                $sheet->mergeCells('A'.self::SUMMARY_TITLE_ROW.':H'.self::SUMMARY_TITLE_ROW);
                $sheet->mergeCells('A'.self::TABLE_TITLE_ROW.':H'.self::TABLE_TITLE_ROW);
                $sheet->freezePane('A'.self::TABLE_START_ROW);

                $sheet->getStyle('A'.self::TITLE_ROW.':H'.self::TITLE_ROW)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '1F5B45'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A'.self::SUMMARY_TITLE_ROW.':H'.self::SUMMARY_TITLE_ROW)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '2B7A5A'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A'.self::TABLE_TITLE_ROW.':H'.self::TABLE_TITLE_ROW)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '2B7A5A'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A'.self::TABLE_HEADING_ROW.':H'.self::TABLE_HEADING_ROW)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '1F5B45'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A'.self::SUMMARY_START_ROW.':B'.self::SUMMARY_END_ROW)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CFE1D8'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A'.self::TABLE_HEADING_ROW.":H{$this->lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D4E1DB'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A'.self::SUMMARY_START_ROW.':A'.self::SUMMARY_END_ROW)->getFont()->setBold(true);
                $sheet->getStyle('A'.self::SUMMARY_START_ROW.':A'.self::SUMMARY_END_ROW)->getFont()->getColor()->setRGB('1F5B45');
                $sheet->getStyle('B'.self::SUMMARY_START_ROW.':B'.self::SUMMARY_END_ROW)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getStyle('A'.self::TABLE_START_ROW.":A{$this->lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B'.self::TABLE_START_ROW.":B{$this->lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C'.self::TABLE_START_ROW.":C{$this->lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D'.self::TABLE_START_ROW.":D{$this->lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E'.self::TABLE_START_ROW.":H{$this->lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('A'.self::TABLE_HEADING_ROW.":H{$this->lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                for ($row = self::TABLE_START_ROW; $row <= $this->lastRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:H{$row}")->getFill()->setFillType(Fill::FILL_SOLID);
                        $sheet->getStyle("A{$row}:H{$row}")->getFill()->getStartColor()->setRGB('F6FBF8');
                    }
                }

                $sheet->getStyle('B'.self::SUMMARY_START_ROW.':B6')->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('B7')->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('B8:B'.self::SUMMARY_END_ROW)->getNumberFormat()->setFormatCode('"Rp" #,##0');

                if (self::TABLE_START_ROW <= $this->lastRow) {
                    $sheet->getStyle('C'.self::TABLE_START_ROW.":C{$this->lastRow}")->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle('E'.self::TABLE_START_ROW.":E{$this->lastRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle('F'.self::TABLE_START_ROW.":H{$this->lastRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
                }

                $sheet->setAutoFilter('A'.self::TABLE_HEADING_ROW.":H{$this->lastRow}");
                $sheet->getRowDimension(self::TITLE_ROW)->setRowHeight(26);
                $sheet->getRowDimension(self::SUMMARY_TITLE_ROW)->setRowHeight(20);
                $sheet->getRowDimension(self::TABLE_TITLE_ROW)->setRowHeight(20);
                $sheet->getRowDimension(self::TABLE_HEADING_ROW)->setRowHeight(21);
            },
        ];
    }
}
