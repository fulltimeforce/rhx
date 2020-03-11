<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->char('id' , 16);
            $table->primary('id');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('positions')->nullable();
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
                'fb']
                )->nullable();
            $table->text('link')->nullable();
            $table->boolean('form')->default(false);
            $table->enum('filter' , ['-','yes', 'no'])->default('-');
            $table->enum('called' , ['-','yes', 'no', 'does not apply'])->default('-');
            $table->enum('scheduled' , ['-','yes', 'no', 'does not apply'])->default('-');
            $table->enum('attended' , ['-','yes', 'no'])->default('-');
            $table->enum('approve' , ['-','yes', 'no'])->default('-');
            $table->char('expert_id' , 16)->nullable();
            $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('recruitment_logs');
    }
}
