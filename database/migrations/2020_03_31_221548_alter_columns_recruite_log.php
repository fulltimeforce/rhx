<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsRecruiteLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruiter_logs', function (Blueprint $table) {
            //
            $table->dropColumn('phone');
            $table->dropColumn('email');
            
        });

        Schema::table('recruiter_logs', function (Blueprint $table) {
            //
            $table->string('info')->after('link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruiter_logs', function (Blueprint $table) {
            //
        });
    }
}
