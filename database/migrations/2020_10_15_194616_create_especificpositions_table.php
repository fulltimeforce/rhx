<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEspecificpositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especificpositions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->nullable();
            $table->string("icon")->default("fullstack_developer");
            $table->enum('status',['enabled','disabled'])->default('enabled');
            $table->text("technology_basic")->nullable();
            $table->text("technology_inter")->nullable();
            $table->text("technology_advan")->nullable();
            $table->boolean('private')->default(true);
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
        Schema::dropIfExists('especificpositions');
    }
}
