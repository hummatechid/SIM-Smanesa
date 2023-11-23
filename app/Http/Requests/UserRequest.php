<?php

namespace App\Http\Requests;

use App\Models\Pengguna;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {
            // Update Rules
            $id = $this->route('pengguna');
            
            // check id from route student has or not
            if(!$id) $id = $this->route('teacher');
            else $user = Pengguna::find($id);
            
            // check id from route teacher has or not 
            if($id) $user = Teacher::find($id);

            return [
                'email' => ['required','email', Rule::unique('users')->ignore($user->user_id)],
                'role_id' => 'required',
            ];
        } else {
            // create rules
            return [
                'email' => 'required|email|unique:users,email',
                'role_id' => 'required',
                'password' => 'required|confirmed'
            ];
        }
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.unique' => 'Alamat email sudah digunakan.',
            'role_id.required' => 'Role wajib dipilih.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Password konfirmasi tidak sama.',
        ];
    }
}
