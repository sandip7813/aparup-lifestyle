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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->enum('page_type', ['blog_page', 'static_page'])->default('blog_page')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->longText('content')->nullable();
            $table->text('short_content')->nullable();
            $table->string('page_title', 255)->nullable();
            $table->text('metadata')->nullable();
            $table->text('keywords')->nullable();
            $table->enum('status', [0, 1, 2])->nullable()->default(1)->comment('0 = inactive, 1 = active, 2 = drafted');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};
