<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInterviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        //
        Schema::create('interviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['technical','psychological','commercial','client'])->nullable();
            $table->string('description');
            $table->boolean('result')->default(0);
            $table->date('date')->nullable();
            $table->char('expert_id' , 16);
            $table->foreign('expert_id')->references('id')->on('experts');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::drop('interviews');
    }
}
