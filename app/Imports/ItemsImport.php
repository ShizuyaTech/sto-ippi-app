<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Item([
            'code' => $row['code'],
            'name' => $row['name'],
            'category' => $row['category'],
            'unit' => $row['unit'],
            'description' => $row['description'] ?? null,
        ]);
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:items,code'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:raw_material,wip,finish_part'],
            'unit' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages(): array
    {
        return [
            'code.required' => 'Code wajib diisi.',
            'code.unique' => 'Code :input sudah ada di database.',
            'name.required' => 'Name wajib diisi.',
            'category.required' => 'Category wajib diisi.',
            'category.in' => 'Category harus: raw_material, wip, atau finish_part.',
            'unit.required' => 'Unit wajib diisi.',
        ];
    }
}
