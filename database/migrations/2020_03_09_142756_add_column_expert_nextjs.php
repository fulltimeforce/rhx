<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnExpertNextjs extends Migration
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
            //
            $table->enum('nextjs',['unknown','basic','intermediate','advanced'])
                ->after('mongoose')
                ->nullable();
            $table->enum('nestjs',['unknown','basic','intermediate','advanced'])
                ->after('nextjs')    
                ->nullable();
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
