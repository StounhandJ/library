<?php

use App\Http\Controllers\AuthController;
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
Route::prefix("auth")
    ->group(function () {
//        Route::middleware("guest")->group(function () {
        Route::post('reg', [AuthController::class, "reg"]);
        Route::post('login', [AuthController::class, "login"]);
//        });

        Route::middleware("role")->group(function () {
            Route::post('logout', [AuthController::class, "logout"]);
            Route::post('refresh', [AuthController::class, "refresh"]);
            Route::post('me', [AuthController::class, "me"]);
            Route::put("me", [AuthController::class, "change"]);
        });
    });

Route::middleware("role")->group(function () {
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
});
