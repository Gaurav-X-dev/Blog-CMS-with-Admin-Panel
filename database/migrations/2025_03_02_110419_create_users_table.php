<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('mobile', 20)->nullable()->unique();
            $table->string('password');
            $table->string('core_password')->nullable(); // Optional unencrypted password storage (not recommended)
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable(); // profile image path
            $table->tinyInteger('status')->default(1); // 1 = active, 0 = inactive
            $table->string('display_name')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); // Vendor or creator user ID

            // Optional: foreign key if you want relational integrity
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->rememberToken(); // for auth login sessions
            $table->timestamps();    // created_at, updated_at
        });
    

    
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

