<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementApplicantsUnionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirement_applicants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('log_id' , 16);
            $table->foreign('log_id')->references('id')->on('logs');
            $table->bigInteger('requirement_id')->unsigned();
            $table->foreign('requirement_id')->references('id')->on('requirements');
            $table->integer('order');
            $table->text('description')->nullable();
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
        // Schema::dropIfExists('requirement_applicants');
    }
}
