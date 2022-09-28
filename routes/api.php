<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource("author", AuthorController::class)
    ->missing(
        fn() => response()->json(["message" => "No query results for model \"Author\""], 404)
    );


Route::apiResource("genre", GenreController::class)
    ->missing(
        fn() => response()->json(["message" => "No query results for model \"Genre\""], 404)
    );


Route::apiResource("book", BookController::class)
    ->missing(
        fn() => response()->json(["message" => "No query results for model \"Book\""], 404)
    );

Route::post("book/{book:id}/genre", [BookController::class, "addGenre"])
    ->missing(
        fn() => response()->json(["message" => "No query results for model \"Book\""], 404)
    );

Route::delete("book/{book:id}/genre", [BookController::class, "delGenre"])
    ->missing(
        fn() => response()->json(["message" => "No query results for model \"Book\""], 404)
    );
