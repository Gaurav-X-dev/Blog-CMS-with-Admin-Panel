<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogTable extends Migration
{
    public function up()
    {
        Schema::create('blog', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('tag')->nullable();
            $table->string('topic')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->unsignedBigInteger('approved_id')->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->dateTime('post_date')->nullable();
            $table->unsignedBigInteger('views')->nullable();
            $table->unsignedBigInteger('is_feature')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('banner')->nullable();
            $table->timestamps();

            // Optional foreign key constraints
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            // $table->foreign('approved_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog');
    }
}
