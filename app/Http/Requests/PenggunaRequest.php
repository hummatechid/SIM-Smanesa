<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenggunaRequest extends FormRequest
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
            'nik' => 'required',
            'full_name' => 'required',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'phone_number' => 'required',
            'address' => 'required',
            'religion' => 'required',
            'bio' => 'nullable',
            'photo' => 'nullable|mimes:jpg, jpeg, png'
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) harus diisi.',
            'full_name.required' => 'Nama lengkap harus diisi.',
            'gender.required' => 'Pilih jenis kelamin.',
            'gender.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
            'phone_number.required' => 'Nomor telepon harus diisi.',
            'address.required' => 'Alamat harus diisi.',
            'religion.required' => 'Pilih agama.',
            'religion.in' => 'Agama harus Islam, Kristen, Katolik, Hindu, Buddha, Konghucu, atau Lainnya.',
            'photo.mimes' => 'Foto harus berformat jpg, jpeg atau png'
        ];     
    }
}
