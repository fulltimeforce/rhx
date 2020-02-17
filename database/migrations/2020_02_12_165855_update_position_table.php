<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE positions MODIFY id CHAR(16)');
        DB::statement('ALTER TABLE experts MODIFY id CHAR(16)');
        DB::statement('ALTER TABLE expert_position MODIFY position_id CHAR(16)');
        DB::statement('ALTER TABLE expert_position MODIFY expert_id CHAR(16)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('positions', function (Blueprint $table) {
            //
        });
    }
}
