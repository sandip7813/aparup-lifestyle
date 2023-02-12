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
        Schema::create('blog_contents', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('blog_uuid');
            $table->string('content_title')->nullable();
            $table->text('content')->nullable();
            $table->json('images')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('blog_contents');
    }
};
