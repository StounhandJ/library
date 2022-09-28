<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FavoriteBookController;
use App\Http\Controllers\GenreController;
use Illuminate\Support\Facades\Route;


// PUBLIC //
Route::middleware("cache.page:5")->group(function () {
    Route::apiResource("author", AuthorController::class)
        ->only("index")
        ->missing(
            fn() => response()->json(["message" => "No query results for model \"Author\""], 404)
        );


    Route::apiResource("genre", GenreController::class)
        ->only("index")
        ->missing(
            fn() => response()->json(["message" => "No query results for model \"Genre\""], 404)
        );


    Route::apiResource("book", BookController::class)
        ->only("index")
        ->missing(
            fn() => response()->json(["message" => "No query results for model \"Book\""], 404)
        );
});

//AUTH//

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

//ALL USER//
Route::middleware("role")->group(function () {
    Route::get("favorites_books", [FavoriteBookController::class, "get"]);
    Route::post("favorites_books", [FavoriteBookController::class, "addBook"]);
    Route::delete("favorites_books", [FavoriteBookController::class, "delBook"]);
});

//ADMIN//
Route::middleware("role:admin")->group(function () {
    Route::apiResource("author", AuthorController::class)
        ->except("index")
        ->missing(
            fn() => response()->json(["message" => "No query results for model \"Author\""], 404)
        );


    Route::apiResource("genre", GenreController::class)
        ->except("index")
        ->missing(
            fn() => response()->json(["message" => "No query results for model \"Genre\""], 404)
        );


    Route::apiResource("book", BookController::class)
        ->except("index")
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
