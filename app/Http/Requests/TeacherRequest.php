<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TeacherRequest extends FormRequest
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
            "nip" => "required",
            "nik" => "nullable",
            "nuptk" => "nullable",
            "full_name" => "required",
            'gender' => 'required|in:Laki-laki,Perempuan',
            'phone_number' => "required",
            "address" => "nullable",
            "religion" => "required",
            "bio" => "nullable",
            "photo" => "nullable|mimes:png,jpg,jpeg",
            "jenis_ptk" => "nullable"
        ];
    }

    public function messages(): array
    {
        return [
            "nip.required" => "Nip tidak boleh kosong",
            "nik.required" => "NIK tidak boleh kosong",
            "nuptk.required" => "NUPTK tidak boleh kosong",
            "full_name.required" => "Nama lengkap tidak boleh kosong",
            "gender.required" => "Jenis kelamin tidak boleh kosong",
            "phone_number.required" => "Nomor telepon tidak boleh kosong",
            "address.required" => "Alamat tidak boleh kosong",
            "religion.required" => "Pilihan agama tidak boleh kosong",
            "jenis_ptk.required" => "Jenis PTK tidak boleh kosong",
            'photo.mimes' => 'Foto harus berformat jpg, jpeg atau png'
        ];   
    }
}
