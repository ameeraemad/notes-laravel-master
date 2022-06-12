<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_statistics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->foreignId('notification_id');
            $table->foreign('notification_id')->references('id')->on('notifications')->cascadeOnDelete();

            $table->enum('send_status', ['Success', 'Failed']);
            $table->enum('seen_status', ['Seen', 'NotSeen'])->default('NotSeen');
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
        Schema::dropIfExists('notification_statistics');
    }
}
