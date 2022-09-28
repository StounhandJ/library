<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;

class FavoriteBookRequest extends FormRequest
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
            "book_id" => "integer|exists:" . Book::class . ",id",
            'books' => 'array',
            'books.*' => "integer|exists:" . Book::class . ",id"
        ];
    }

    function getBooks()
    {
        if ($this->exists("book_id")) {
            return [$this->get("book_id")];
        } else {
            if ($this->exists("books")) {
                return $this->get("books");
            } else {
                return null;
            }
        }
    }
}
