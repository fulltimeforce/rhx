<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruit_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('recruit_id');
            $table->string('position_id');
            $table->string('user_id')->nullable();
            $table->string('outstanding_report')->nullable();
            $table->string('call_report')->nullable();
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
        Schema::dropIfExists('recruit_positions');
    }
}
