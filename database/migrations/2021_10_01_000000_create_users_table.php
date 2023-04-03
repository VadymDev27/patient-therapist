<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_therapist')->nullable();
            $table->unsignedBigInteger('pair_id')->nullable();
            $table->foreign('pair_id')->references('id')->on('pairs')->cascadeOnDelete();
            $table->timestamps();
            $table->tinyInteger('week')->nullable();
            $table->boolean('is_eligible')->nullable();
            $table->dateTime('last_week_completed_at')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->json('admin_permissions')->default(json_encode([]));
            $table->boolean('is_test')->default(false);
            $table->boolean('test_time_travel')->default(false);
            $table->boolean('test_can_go_ahead')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
