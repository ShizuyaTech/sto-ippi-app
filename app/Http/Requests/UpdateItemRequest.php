<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $itemId = $this->route('item');
        
        return [
            'code' => ['required', 'string', "unique:items,code,{$itemId}", 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:raw_material,wip,finish_part'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:50'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Kode item harus diisi.',
            'code.unique' => 'Kode item sudah terdaftar.',
            'name.required' => 'Nama item harus diisi.',
            'category.required' => 'Kategori harus dipilih.',
            'category.in' => 'Kategori tidak valid.',
            'unit.required' => 'Satuan harus diisi.',
        ];
    }
}
