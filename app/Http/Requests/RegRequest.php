<?php

namespace App\Http\Requests;

use App\Http\Enum\Gender;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            "surname" => "string|min:1|max:255",
            "name" => "string|min:1|max:255",
            "patronymic" => "string|min:1|max:255",
            "login" => "required|string|min:5|max:255",
            "password" => "required|string|min:5|max:255",
            "birthday" => "date",
            "role" => [
                "required",
                "in:user,admin"
            ],
            "gender" => [
                "in:man,woman"
            ],

        ];
    }
}
