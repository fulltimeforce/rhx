<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSlugPosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('positions', function (Blueprint $table) {
            //
            $table->string('slug')
                ->nullable()
                ->after('description');
        });

        $positions = DB::table('positions')->select('id', 'name')->get();

        foreach ($positions as $key => $position) {
            DB::table('positions')
                ->where('id',$position->id)
                ->update([
                    "slug" => preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($position->name))
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('positions', function (Blueprint $table) {
            //
        });
    }
}
