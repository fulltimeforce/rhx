<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsRecruiterLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('recruiter_logs', function (Blueprint $table) {
            $table->dropColumn('filter');
            $table->dropColumn('called');
            $table->dropColumn('scheduled');
            $table->dropColumn('attended');
            $table->dropColumn('approve');

        });
        Schema::table('recruiter_logs', function (Blueprint $table) {
            $table->string('phone')->after('link')->nullable();
            $table->string('email')->after('phone')->nullable();

            $table->enum('contact', 
                ['contacted', 
                'not respond',
                'dont want', 
                'not available' , 
                'num email incorrect' ,
                'submitted form' , 
                'filled form']
                )->after('email')->nullable();

            $table->enum('cv' , 
                ['approved',
                'not approved'])->after('contact')->nullable();
            $table->enum('experience' , 
                ['approved',
                'not approved'])->after('cv')->nullable();
            $table->enum('communication' , 
                ['approved',
                'not approved'])->after('experience')->nullable();
            $table->enum('english' , 
                ['approved',
                'not approved'])->after('communication')->nullable();

            $table->enum('schedule' , 
                ['scheduled',
                'dont want'])->after('english')->nullable();

            $table->enum('commercial' , 
                ['approved',
                'not approved',
                'not show up'])->after('schedule')->nullable();

            $table->enum('technique' , 
                ['approved',
                'not approved',
                'not show up'])->after('commercial')->nullable();
            $table->enum('psychology' , 
                ['approved',
                'not approved',
                'not show up'])->after('technique')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
