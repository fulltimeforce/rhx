<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsFce extends Migration
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
            $list = array('C2','C1','B2','B1','A2','A1');

            $table->double('grammar_vocabulary')->next('user_id')->default(0);
            $table->enum('grammatical_forms', $list )->next('grammar_vocabulary')->nullable();
            $table->enum('vocabulary', $list )->next('grammatical_forms')->nullable();

            $table->double('discourse_management' )->next('vocabulary')->default(0);
            $table->enum('stretch_language', $list )->next('discourse_management')->nullable();
            $table->enum('cohesive_devices', $list )->next('stretch_language')->nullable();
            $table->enum('hesitation', $list )->next('cohesive_devices')->nullable();
            $table->enum('organizations_ideas', $list )->next('hesitation')->nullable();

            $table->double('pronunciation')->next('organizations_ideas')->default(0);
            $table->enum('intonation', $list )->next('pronunciation')->nullable();
            $table->enum('phonological_features', $list )->next('intonation')->nullable();
            $table->enum('intelligible', $list )->next('phonological_features')->nullable();

            $table->double('interactive_communication')->next('intelligible')->default(0);
            $table->enum('interaction', $list )->next('interactive_communication')->nullable();
            
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
