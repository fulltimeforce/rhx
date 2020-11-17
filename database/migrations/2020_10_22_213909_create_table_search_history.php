<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSearchHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id')->nullable();
            $table->string('search')->nullable();
            $table->string('basic')->nullable();
            $table->string('intermediate')->nullable();
            $table->string('advanced')->nullable();
            $table->string('audio')->nullable();
            $table->string('selection')->nullable();
            $table->string('name')->nullable();
            $table->string('search_notify_options')->nullable();
            $table->string('search_user_level')->nullable();
            $table->string('search_name')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE search_history MODIFY id CHAR(16)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_history');
    }
}
