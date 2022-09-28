<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites_books', function (Blueprint $table) {
            $table->foreignId("user_id")->references('id')->on('users');
            $table->foreignId("book_id")->references('id')->on('books');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum("gender", ["man", "woman"])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites_books');
    }
};
