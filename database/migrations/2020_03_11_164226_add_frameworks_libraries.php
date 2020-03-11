<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFrameworksLibraries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experts', function (Blueprint $table) {
            $table->enum('fabricjs',['unknown','basic','intermediate','advanced'])
            ->after('nestjs')
            ->nullable()->default('unknown');
            $table->enum('d3js',['unknown','basic','intermediate','advanced'])
                ->after('fabricjs')    
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
        Schema::table('experts', function (Blueprint $table) {
            //
        });
    }
}
