<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname');
            $table->string('identification_number');
            $table->string('platform');
            $table->string('phone_number');
            $table->string('email_address');
            $table->string('profile_link')->nullable();;
            $table->string('file_path')->nullable();;
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
        Schema::dropIfExists('recruits');
    }
}
