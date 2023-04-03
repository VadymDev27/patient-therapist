<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('number');
            $table->string('video_id');
            $table->string('video_title');
            $table->string('exercises_title')->nullable();
            $table->boolean('prep');
            $table->unique(['number','prep']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weekly_settings');
    }
}
