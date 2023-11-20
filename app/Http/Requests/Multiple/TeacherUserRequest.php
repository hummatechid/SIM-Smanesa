<?php

namespace App\Http\Requests\Multiple;

use App\Http\Requests\TeacherRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Foundation\Http\FormRequest;

class TeacherUserRequest extends FormRequest
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
        $teacher = (new TeacherRequest())->rules();
        $user = (new UserRequest())->rules();
        
        return array_merge(
            $teacher, 
            $user
        );
    }
}
