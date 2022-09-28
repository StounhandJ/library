<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "surname" => "string|min:1|max:255",
            "name" => "string|min:1|max:255",
            "patronymic" => "string|min:1|max:255",
            "login" => "string|min:5|max:255",
            "password" => "string|min:5|max:255",
            "birthday" => "date",
            "role" => [
                "in:user,admin"
            ],
            "gender" => [
                "in:man,woman"
            ],
        ];
    }
}
