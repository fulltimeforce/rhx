<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumns3ToRecruitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruit', function (Blueprint $table) {
            $list = array('C2','C1','B2','B1','A2','A1');

            $table->double('grammar_vocabulary')->after('tech_qtn')->default(0);
            $table->enum('grammatical_forms', $list )->after('grammar_vocabulary')->nullable();
            $table->enum('vocabulary', $list )->after('grammatical_forms')->nullable();

            $table->double('discourse_management' )->after('vocabulary')->default(0);
            $table->enum('stretch_language', $list )->after('discourse_management')->nullable();
            $table->enum('cohesive_devices', $list )->after('stretch_language')->nullable();
            $table->enum('hesitation', $list )->after('cohesive_devices')->nullable();
            $table->enum('organizations_ideas', $list )->after('hesitation')->nullable();

            $table->double('pronunciation')->after('organizations_ideas')->default(0);
            $table->enum('intonation', $list )->after('pronunciation')->nullable();
            $table->enum('phonological_features', $list )->after('intonation')->nullable();
            $table->enum('intelligible', $list )->after('phonological_features')->nullable();

            $table->double('interactive_communication')->after('intelligible')->default(0);
            $table->enum('interaction', $list )->after('interactive_communication')->nullable();

            $table->enum('fce_overall' ,['-','A1-','A1','A1+','A2-','A2','A2+','B1-','B1','B1+','B2-','B2','B2+','C1-','C1','C1+','C2-','C2','C2+'])->after('interaction')->default('-');
            $table->float('fce_total' , 8, 2)->after('fce_overall')->default(0);
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
