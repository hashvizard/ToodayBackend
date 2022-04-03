<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->nullable();
            $table->string('password');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('profile')->nullable();
            $table->foreignId('city_id')->nullable() ->references('id')->on('cities');
            $table->integer('comments')->default(0);
            $table->integer('views')->default(0);
            $table->integer('posts')->default(0);
            $table->integer('reviews')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
