<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentRequest extends FormRequest
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
            'category' => 'required|string',
            'min_score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric',
            'action' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'Kategori tidak boleh kosong',
            'category.string' => 'Kategori harus berupa huruf',
            'min_score.required' => 'Minimal score tidak boleh kosong',
            'min_score.numeric' => 'Minimal score harus berupa angka',
            'min_score.min' => 'Minimal score adalah 0',
            'max_score.required' => 'maximal score tidak boleh kosong',
            'max_score.numeric' => 'maximal score harus berupa angka',
            'action.required' => 'Aksi tidak boleh kosong'
        ];
    }
}
