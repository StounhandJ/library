<?php

namespace App\Http\Requests;

use App\Models\Genre;
use Illuminate\Foundation\Http\FormRequest;

class BookCreateRequest extends FormRequest
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
            "name" => "required|string|min:1|max:255",
            "description" => "required|string",
            "date_publication" => "required|date",
            "cover" => "required|image",
            "genre_id" => "integer|exists:" . Genre::class . ",id",
            'genres' => 'array',
            'genres.*' => "integer|exists:" . Genre::class . ",id"
        ];
    }

    function getGenres()
    {
        if ($this->exists("genre_id")) {
            return [$this->get("genre_id")];
        } else {
            if ($this->exists("genres")) {
                return $this->get("genres");
            } else {
                return null;
            }
        }
    }
}
