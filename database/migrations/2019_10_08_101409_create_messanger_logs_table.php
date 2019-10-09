<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessangerLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messanger_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('message');
            $table->char('contact',50);
            $table->enum('messenger',['Telegram','WhatsApp','Viber']);
            $table->integer('count')->default(0);
            $table->boolean('done')->default(false);
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
        Schema::dropIfExists('messanger_logs');
    }
}
