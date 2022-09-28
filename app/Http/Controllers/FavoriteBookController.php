<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteBookRequest;
use Illuminate\Http\JsonResponse;

class FavoriteBookController extends Controller
{
    /**
     * @param FavoriteBookRequest $request
     * @return JsonResponse
     */
    public function addBook(FavoriteBookRequest $request): JsonResponse
    {
        $genres = $request->getBooks();
        if (is_null($genres)) {
            return response()->json(["message" => "The books or book_id parameter must be specified", "errors" => []],
                422);
        }
        $user = auth()->user();
        $user->favorites_books()->attach($genres);

        return response()->json(["message" => "success", "response" => $user->favorites_books()->get()]);
    }

    /**
     * @param FavoriteBookRequest $request
     * @return JsonResponse
     */
    public function delBook(FavoriteBookRequest $request): JsonResponse
    {
        $genres = $request->getBooks();
        if (is_null($genres)) {
            return response()->json(["message" => "The books or book_id parameter must be specified", "errors" => []],
                422);
        }
        $user = auth()->user();
        $user->favorites_books()->detach($genres);

        return response()->json(["message" => "success", "response" => $user->favorites_books()->get()]);
    }

    /**
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        return response()->json(["message" => "success", "response" => auth()->user()->favorites_books()->get()]);
    }
}
