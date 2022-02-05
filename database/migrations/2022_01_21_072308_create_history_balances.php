<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryBalances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_balances', function (Blueprint $table) {
            $table->id();
            $table->string('received_service_id');
            $table->string('service_id');
            $table->integer('quantity');
            $table->double('oldBalance');
            $table->double('newBalance');
            $table->string('user_id');
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
        Schema::dropIfExists('history_balances');
    }
}
