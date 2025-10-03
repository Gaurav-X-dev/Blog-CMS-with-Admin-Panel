<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('tag')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->unsignedBigInteger('approved_id')->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->json('social_data')->nullable(); // Assuming it's a JSON object
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->string('banner')->nullable();
            $table->timestamps();

            // Optional: foreign keys
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            // $table->foreign('approved_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
}

