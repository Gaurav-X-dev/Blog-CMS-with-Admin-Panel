<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingTable extends Migration
{
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->id(); // Primary key (id)
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('landline', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('logo')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('website')->nullable();
            $table->string('google_a')->nullable();      // Google Analytics or Ads
            $table->string('google_web')->nullable();    // Google Webmaster or Tag
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('setting');
    }
}

