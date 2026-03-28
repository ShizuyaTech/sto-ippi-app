<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockTakingSessionRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'category' => ['required', 'in:raw_material,wip,finish_part'],
            'scheduled_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User harus dipilih.',
            'user_id.exists' => 'User tidak ditemukan.',
            'category.required' => 'Kategori harus dipilih.',
            'category.in' => 'Kategori tidak valid.',
            'scheduled_date.required' => 'Tanggal harus diisi.',
            'scheduled_date.date' => 'Format tanggal tidak valid.',
        ];
    }
}
