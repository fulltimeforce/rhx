<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumns2ToRecruitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruit', function (Blueprint $table) {
            $table->string('crit_2')->after('audio_path')->nullable();
            $table->string('crit_1')->after('audio_path')->nullable();
            $table->string('crit_english')->after('audio_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruit', function (Blueprint $table) {
            //
        });
    }
}
