<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToRecruitPositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruit_positions', function (Blueprint $table) {
            $table->timestamp('outstanding_ev_date')->nullable()->after('soft_report');
            $table->timestamp('call_ev_date')->nullable()->after('outstanding_ev_date');
            $table->timestamp('audio_ev_date')->nullable()->after('call_ev_date');
            $table->timestamp('soft_ev_date')->nullable()->after('audio_ev_date');
            $table->text('audio_notes')->nullable()->after('soft_ev_date');
            $table->text('evaluation_notes')->nullable()->after('audio_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruit_positions', function (Blueprint $table) {
            //
        });
    }
}
