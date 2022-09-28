<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Author;
use App\Models\Book;

class SearchController extends Controller
{
    public function book(SearchRequest $request): \Illuminate\Http\JsonResponse
    {
        return  response()->json(["message" => "success", "products" => Book::search($request)->get()]);
    }

    public function author(SearchRequest $request): \Illuminate\Http\JsonResponse
    {
        return  response()->json(["message" => "success", "products" => Author::search($request)->get()]);
    }
}
