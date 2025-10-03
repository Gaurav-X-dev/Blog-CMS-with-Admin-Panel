<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
    {
        Schema::create('user_stories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->string('title');                      // ✅ Story title
            $table->string('slug');                       // ✅ SEO-friendly slug
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('page_link')->nullable(); // Single URL
            $table->text('description');
            $table->string('tag')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stories');
    }
};
