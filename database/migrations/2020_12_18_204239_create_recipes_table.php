<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->integer('userId');
            $table->string('name');
            $table->longText('image')->nullable();
            $table->integer('duration');
            $table->integer('noOfServing');
            $table->string('difficulty');
            $table->integer('cuisine_id')->nullable();
            $table->longText('ingredients');
            $table->longText('steps');
            $table->string('websiteUrl')->nullable();
            $table->string('youtubeUrl')->nullable();
            $table->integer('noOfViews')->nullable()->default(0);
            $table->integer('noOfLikes')->nullable()->default(0);
            $table->boolean('status')->nullable()->default(1);
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
        Schema::dropIfExists('recipes');
    }
}
