<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TagNumbersExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $tagNumbers;
    protected $category;

    public function __construct(array $tagNumbers, string $category)
    {
        $this->tagNumbers = $tagNumbers;
        $this->category = $category;
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->tagNumbers as $index => $tagNumber) {
            $data[] = [
                'no' => $index + 1,
                'tag_number' => $tagNumber,
                'category' => $this->getCategoryLabel(),
                'location' => '',
                'remarks' => '',
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tag Number',
            'Category',
            'Location',
            'Remarks',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '667eea']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 25,
            'C' => 20,
            'D' => 20,
            'E' => 30,
        ];
    }

    private function getCategoryLabel(): string
    {
        $labels = [
            'raw_material' => 'Raw Material',
            'wip' => 'WIP',
            'finish_part' => 'Finish Part',
        ];
        return $labels[$this->category] ?? $this->category;
    }
}
