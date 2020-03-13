<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expert_position', function (Blueprint $table) {
            //
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
                )->nullable()->after('position_id');
            $table->text('link')->nullable()->after('platform');
            $table->boolean('form')->default(false)->after('link');
            $table->enum('filter' , ['-','yes', 'no'])->default('-')->after('form');
            $table->enum('called' , ['-','yes', 'no', 'does not apply'])->default('-')->after('filter');
            $table->enum('scheduled' , ['-','yes', 'no', 'does not apply'])->default('-')->after('called');
            $table->enum('attended' , ['-','yes', 'no'])->default('-')->after('scheduled');
            $table->enum('approve' , ['-','yes', 'no'])->default('-')->after('attended');
            $table->integer('user_id')->unsigned()->after('approve');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expert_position', function (Blueprint $table) {
            //
        });
    }
}
