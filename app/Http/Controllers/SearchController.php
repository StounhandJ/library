<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Author;
use App\Models\Book;

class SearchController extends Controller
{
    public function book(SearchRequest $request): \Illuminate\Http\JsonResponse
    {
        return  response()->json(["message" => "success", "response" => Book::search($request->get("name"))->get()]);
    }

    public function author(SearchRequest $request): \Illuminate\Http\JsonResponse
    {
        return  response()->json(["message" => "success", "response" => Author::search($request->get("name"))->get()]);
    }
}
