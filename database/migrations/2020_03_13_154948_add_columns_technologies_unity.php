<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsTechnologiesUnity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experts', function (Blueprint $table) {
            //
            $table->enum('3dmodeling',['unknown','basic','intermediate','advanced'])->nullable()->default('unknown')->after('screwdriver');
            $table->enum('csharpu',['unknown','basic','intermediate','advanced'])->nullable()->default('unknown')->after('3dmodeling');
            $table->enum('animation',['unknown','basic','intermediate','advanced'])->nullable()->default('unknown')->after('csharpu');
            $table->enum('physics',['unknown','basic','intermediate','advanced'])->nullable()->default('unknown')->after('animation');
            $table->enum('networking',['unknown','basic','intermediate','advanced'])->nullable()->default('unknown')->after('physics');
            $table->enum('vr',['unknown','basic','intermediate','advanced'])->nullable()->default('unknown')->after('networking');
            $table->enum('graphics',['unknown','basic','intermediate','advanced'])->nullable()->default('unknown')->after('vr');
            $table->enum('ui',['unknown','basic','intermediate','advanced'])->nullable()->default('unknown')->after('graphics');
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
