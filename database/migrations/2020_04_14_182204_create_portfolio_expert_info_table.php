<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfolioExpertInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_expert', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('expert_id' , 16);
            $table->string('fullname')->nullable();
            $table->text('work')->nullable();
            $table->integer('age')->nullable();
            $table->text('email')->nullable();
            $table->text('address')->nullable();
            $table->text('github')->nullable();
            $table->text('linkedin')->nullable();
            $table->text('facebook')->nullable();
            $table->text('photo')->nullable();
            $table->text('description')->nullable();
            $table->text('resume')->nullable();
            $table->text('education')->nullable(); // serialize array
            $table->text('employment')->nullable(); // serialize array
            $table->text('skills')->nullable(); // serialize array
            $table->text('projects')->nullable(); // serialize array
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolio_expert');
    }
}
