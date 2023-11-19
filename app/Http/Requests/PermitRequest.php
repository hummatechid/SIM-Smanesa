<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermitRequest extends FormRequest
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
            'student_id' => 'required',
            'created_by' => 'nullable',
            'reason' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Data siswa/siwsi harus diisi',
            'reason.required' => 'Keterangan izin harus diisi',
        ];
    }
}
