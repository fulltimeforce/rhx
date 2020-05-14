<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsAudioLogsTable extends Migration
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
            $table->text('filter_audio')->after('psychology')->nullable();
            $table->text('evaluate_audio')->after('filter_audio')->nullable();
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
