<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTechnologyFrontedGatsbyjs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('experts', function (Blueprint $table) {
            $table->enum('gatsbyjs',['unknown','basic','intermediate','advanced'])
            ->after('typescript')
            ->nullable()->default('unknown');
            
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
