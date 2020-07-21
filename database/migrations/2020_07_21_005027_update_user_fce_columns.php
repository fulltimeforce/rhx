<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserFceColumns extends Migration
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
            $table->enum('fce_grammatical_forms',['-','A1','A2','B1','B2','C1','C2'])->after('fce_grammar_vocabulary')->default('-');
            $table->enum('fce_vocabulary',['-','A1','A2','B1','B2','C1','C2'])->after('fce_grammatical_forms')->default('-');
            $table->enum('fce_stretch_of_language',['-','A1','A2','B1','B2','C1','C2'])->after('fce_discourse_management')->default('-');
            $table->enum('fce_repetition' ,['-','A1','A2','B1','B2','C1','C2'])->after('fce_stretch_of_language')->default('-');
            $table->enum('fce_cohesive_devices' ,['-','A1','A2','B1','B2','C1','C2'])->after('fce_repetition')->default('-');
            $table->enum('fce_hesitation' ,['-','A1','A2','B1','B2','C1','C2'])->after('fce_cohesive_devices')->default('-');
            $table->enum('fce_contributions' ,['-','A1','A2','B1','B2','C1','C2'])->after('fce_hesitation')->default('-');
            $table->enum('fce_intonation' ,['-','A1','A2','B1','B2','C1','C2'])->after('fce_pronunciation')->default('-');
            $table->enum('fce_phonological_features' ,['-','A1','A2','B1','B2','C1','C2'])->after('fce_intonation')->default('-');
            $table->enum('fce_intelligible' ,['-','A1','A2','B1','B2','C1','C2'])->after('fce_phonological_features')->default('-');
            $table->enum('fce_interaction' ,['-','A1','A2','B1','B2','C1','C2'])->after('fce_interactive_communication')->default('-');
            $table->enum('fce_initiative' ,['-','A1','A2','B1','B2','C1','C2'])->after('fce_interaction')->default('-');
            
            $table->dropColumn('fce_comments');
            $table->dropColumn('fce_overall');
        });

        Schema::table('experts', function (Blueprint $table) {
            $table->enum('fce_overall' ,['-','A1-','A1','A1+','A2-','A2','A2+','B1-','B1','B1+','B2-','B2','B2+','C1-','C1','C1+','C2-','C2','C2+'])->after('fce_initiative')->default('-');
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
