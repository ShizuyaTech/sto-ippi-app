<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsTemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * Return sample data for template
     */
    public function array(): array
    {
        return [
            [
                'ITEM-001',
                'Steel Plate 10mm',
                'raw_material',
                'Sheet',
                'Steel plate 10mm thickness'
            ],
            [
                'WIP-001',
                'Semi-finished Bracket',
                'wip',
                'Pcs',
                'Bracket in production'
            ],
            [
                'FIN-001',
                'Finished Bracket Model A',
                'finish_part',
                'Pcs',
                'Completed bracket ready to ship'
            ],
        ];
    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'code',
            'name',
            'category',
            'unit',
            'description'
        ];
    }

    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
