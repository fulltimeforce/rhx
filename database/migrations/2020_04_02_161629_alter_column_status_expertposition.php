<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnStatusExpertposition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('expert_position', function (Blueprint $table) {
            //
            $table->dropColumn('status');
            
        });

        Schema::table('expert_position', function (Blueprint $table) {
            //
            $table->enum('status' , 
                [
                'disqualified',
                'qualified'])->after('approve')->default('disqualified');
            
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
