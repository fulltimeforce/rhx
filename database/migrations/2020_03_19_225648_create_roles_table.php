<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Vinkla\Hashids\Facades\Hashids;

use App\User;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        DB::table('roles')->insert(array(
            array(
                "name"  => "SUPERADMIN",
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ),
            array(
                "name"  => "RECRUITER",
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ),
        ));

        Schema::table('users', function (Blueprint $table) {
            //
            $table->integer('role_id');
        });

        foreach ( User::all() as $key => $user) {
            if($user->id == 1 ){
                User::where('id',$user->id)->update(array('role_id' => 1));
            }else{
                User::where('id',$user->id)->update(array('role_id' => 2));
            }
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
