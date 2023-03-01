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
        Schema::table('blogs', function (Blueprint $table) {
            $table->dateTime('scheduled_at')->nullable()->after('short_content');
        });

        \DB::statement("ALTER TABLE blogs MODIFY COLUMN status ENUM('0', '1', '2', '3') DEFAULT '1' COMMENT '0 = inactive, 1 = active, 2 = drafted, 3 = scheduled'"); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            //
        });
    }
};
