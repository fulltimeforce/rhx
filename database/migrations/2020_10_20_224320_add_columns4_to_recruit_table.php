<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumns4ToRecruitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruit', function (Blueprint $table) {
            $table->date('birthday')->nullable()->after('email_address');
            $table->string('education')->nullable()->after('birthday');
            $table->enum('english_education',['academy','university','self'])->nullable()->after('education');
            $table->string('address')->nullable()->after('english_education');
            $table->enum('type_money' , ['sol' , 'dolar'])->default('sol')->after('address');
            $table->string('salary')->nullable()->after('type_money');
            $table->string('availability')->nullable()->after('salary');
            $table->text('linkedin')->nullable()->after('availability');
            $table->text('github')->nullable()->after('linkedin');
            $table->text('twitter')->nullable()->after('github');
            $table->text('other_knowledge')->nullable()->after('twitter');
            $table->text('wish_knowledge')->nullable()->after('other_knowledge');
            $table->enum('focus',['backend','frontend','mobile','devops','fullstack','game'])->nullable()->after('wish_knowledge');
            $table->integer('selection')->after('id')->default(1);
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
