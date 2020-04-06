<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruiter_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->nullable();
            $table->text('expert')->nullable();
            $table->char('position_id' , 16)->nullable();
            $table->integer('user_id')->nullable();
            $table->enum('platform', 
                ['linkedin', 
                'computrabajo',
                'indeed', 
                'getonboard' , 
                'bumeran' ,
                'catolica' , 
                'upc' , 
                'ulima' , 
                'ricardopalma', 
                'utp' , 
                'fb',
                'email']
                )->nullable();
            $table->text('link')->nullable();
            $table->boolean('filter')->default(false);
            $table->boolean('called')->default(false);
            $table->boolean('scheduled')->default(false);
            $table->boolean('attended')->default(false);
            $table->boolean('approve')->default(false);
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
        Schema::dropIfExists('recruiter_logs');
    }
}
