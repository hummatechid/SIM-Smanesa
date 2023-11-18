<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViolationTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
            "score" => "required|numeric"
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "Nama pelanggaran tidak boleh kosong",
            "name.string" => "Nama pelanggaran harus berupa huruf",
            "score.required" => "Poin pelanggaran harus diis tidak boleh kosong",
            "score.numeric" => "Poin pelanggaran harus berupa angka"
        ];
    }
}
