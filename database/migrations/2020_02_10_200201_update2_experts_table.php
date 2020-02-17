<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Update2ExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('experts', function (Blueprint $table) {
        //     $table->dropColumn('keroku');
        //     $techs = array(
        //         'heroku',
        //     );
        //     foreach($techs as $tech){
        //         $table->enum($tech,['unknown','basic','intermediate','advanced'])->nullable();
        //     }
        // });
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
