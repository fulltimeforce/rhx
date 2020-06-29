<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFceToTableExperts extends Migration
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
            $table->float('fce_grammar_vocabulary' , 8, 2)->after('user_id')->default(0);
            $table->float('fce_discourse_management' , 8, 2)->after('fce_grammar_vocabulary')->default(0);
            $table->float('fce_pronunciation' , 8, 2)->after('fce_discourse_management')->default(0);
            $table->float('fce_interactive_communication' , 8, 2)->after('fce_pronunciation')->default(0);
            $table->float('fce_total' , 8, 2)->after('fce_interactive_communication')->default(0);
            $table->string('fce_overall' )->after('fce_total')->default('');
            $table->text('fce_comments' )->after('fce_overall')->default('');
            

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
