<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockTakingDetailRequest extends FormRequest
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
        return [
            'tag_number' => ['required', 'string', 'max:50'],
            'item_id' => ['required', 'exists:items,id'],
            'actual_quantity' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'tag_number.required' => 'Tag Number harus diisi.',
            'tag_number.string' => 'Tag Number harus berupa teks.',
            'tag_number.max' => 'Tag Number maksimal 50 karakter.',
            'item_id.required' => 'Item harus dipilih.',
            'item_id.exists' => 'Item tidak ditemukan.',
            'actual_quantity.required' => 'Qty aktual harus diisi.',
            'actual_quantity.numeric' => 'Qty aktual harus berupa angka.',
        ];
    }
}
