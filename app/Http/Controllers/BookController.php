<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookGenreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(["message" => "success", "response" => Book::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookCreateRequest $request
     * @return JsonResponse
     */
    public function store(BookCreateRequest $request): JsonResponse
    {
        $genres = $request->getGenres();
        if (is_null($genres)) {
            return response()->json(["message" => "The genres or genre_id parameter must be specified", "errors" => []],
                422);
        }

        /** @var Book $book */
        $book = Book::query()->make($request->only(["name", "description", "date_publication"]));

        $book->setImgSrcIfNotEmpty($request->file("cover"));
        $book->genres()->attach($genres);
        $book->save();

        return response()->json(["message" => "success", "response" => $book]);
    }

    /**
     * Display the specified resource.
     *
     * @param Book $book
     * @return JsonResponse
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json(["message" => "success", "response" => $book]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BookUpdateRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function update(BookUpdateRequest $request, Book $book): JsonResponse
    {
        $book->setImgSrcIfNotEmpty($request->file("cover"));

        if ($request->exists("name")) {
            $book->name = $request->get("name");
        }

        if ($request->exists("description")) {
            $book->description = $request->get("description");
        }

        if ($request->exists("date_publication")) {
            $book->date_publication = $request->get("date_publication");
        }

        $genres = $request->getGenres();
        if (!is_null($genres)) {
            $book->genres()->sync($genres);
        }

        $book->save();

        return response()->json(["message" => "success", "response" => $book]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Book $book
     * @return JsonResponse
     */
    public function destroy(Book $book): JsonResponse
    {
        $result = $book->delete();
        return response()->json(["message" => $result ? "success" : "error"], $result ? 200 : 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BookGenreRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function addGenre(BookGenreRequest $request, Book $book): JsonResponse
    {
        $genres = $request->getGenres();
        if (is_null($genres)) {
            return response()->json(["message" => "The genres or genre_id parameter must be specified", "errors" => []],
                422);
        }
        $book->genres()->attach($genres);

        return response()->json(["message" => "success", "response" => $book]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BookGenreRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function delGenre(BookGenreRequest $request, Book $book): JsonResponse
    {
        $genres = $request->getGenres();
        if (is_null($genres)) {
            return response()->json(["message" => "The genres or genre_id parameter must be specified", "errors" => []],
                422);
        }
        $book->genres()->detach($genres);

        return response()->json(["message" => "success", "response" => $book]);
    }
}
