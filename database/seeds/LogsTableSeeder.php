<?php

use Illuminate\Database\Seeder;
use Vinkla\Hashids\Facades\Hashids;

use App\Log;
use App\Position;

class LogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $platform = Log::getPlataforms();
        $positions = Position::get();

        $a_ids = array();
        foreach ($positions as $key => $position) {
            $a_ids[] = $position->id;
        }

        for ($i=0; $i < 50 ; $i++) { 
            DB::table('logs')->insert([
                array(
                    'id' => Hashids::encode(time() + $i*123),
                    'name' => $this->randomName(),
                    'phone' => '985555622214',
                    'positions' => $a_ids[ rand( 0 , 4) ],
                    'platform' => $platform[ rand( 0 , 10) ]["id"],
                    'link' => 'URLRLRRLRLRLRLLRRLRLLRLRLRLRLR',
                    'form' => 0,
                    'filter' => '-',
                    'called' => 'no',
                    'scheduled' => 'does not apply',
                    'attended' => 'yes',
                    'approve' => 'no',
                    'user_id' => 5
                ),

            ]);
        }

    }


    private function randomName() {
        $firstname = array(
            'Johnathon',
            'Anthony',
            'Erasmo',
            'Raleigh',
            'Nancie',
            'Tama',
            'Camellia',
            'Augustine',
            'Christeen',
            'Luz',
            'Diego',
            'Lyndia',
            'Thomas',
            'Georgianna',
            'Leigha',
            'Alejandro',
            'Marquis',
            'Joan',
            'Stephania',
            'Elroy',
            'Zonia',
            'Buffy',
            'Sharie',
            'Blythe',
            'Gaylene',
            'Elida',
            'Randy',
            'Margarete',
            'Margarett',
            'Dion',
            'Tomi',
            'Arden',
            'Clora',
            'Laine',
            'Becki',
            'Margherita',
            'Bong',
            'Jeanice',
            'Qiana',
            'Lawanda',
            'Rebecka',
            'Maribel',
            'Tami',
            'Yuri',
            'Michele',
            'Rubi',
            'Larisa',
            'Lloyd',
            'Tyisha',
            'Samatha',
        );
    
        $lastname = array(
            'Mischke',
            'Serna',
            'Pingree',
            'Mcnaught',
            'Pepper',
            'Schildgen',
            'Mongold',
            'Wrona',
            'Geddes',
            'Lanz',
            'Fetzer',
            'Schroeder',
            'Block',
            'Mayoral',
            'Fleishman',
            'Roberie',
            'Latson',
            'Lupo',
            'Motsinger',
            'Drews',
            'Coby',
            'Redner',
            'Culton',
            'Howe',
            'Stoval',
            'Michaud',
            'Mote',
            'Menjivar',
            'Wiers',
            'Paris',
            'Grisby',
            'Noren',
            'Damron',
            'Kazmierczak',
            'Haslett',
            'Guillemette',
            'Buresh',
            'Center',
            'Kucera',
            'Catt',
            'Badon',
            'Grumbles',
            'Antes',
            'Byron',
            'Volkman',
            'Klemp',
            'Pekar',
            'Pecora',
            'Schewe',
            'Ramage',
        );
    
        $name = $firstname[rand ( 0 , count($firstname) -1)];
        $name .= ' ';
        $name .= $lastname[rand ( 0 , count($lastname) -1)];
    
        return $name;
    }
}
