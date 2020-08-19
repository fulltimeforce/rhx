<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Role::create(['name' => 'SUPERADMIN']);
        $recruiter = Role::create(['name' => 'RECRUITER']);
        
        //JUST IN CASE THE SYSTEM ALREADY HAVE DATA, But we can disscuss in removing this lines
        User::where('id', 1)->update(['role_id' => $superadmin->id]);
        User::where('id', '<>' , 1)->update(['role_id' => $recruiter->id]);
    }
}
