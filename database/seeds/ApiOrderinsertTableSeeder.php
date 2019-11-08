<?php

use Illuminate\Database\Seeder;
use App\ApiOrderinsert;

class ApiOrderinsertTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ApiOrderinsert = new ApiOrderinsert();
        $ApiOrderinsert->team = 'goldteamfrom';
        $ApiOrderinsert->value = 0;
        $ApiOrderinsert->created_by = 'Admin';
        $ApiOrderinsert->save();

        $ApiOrderinsert = new ApiOrderinsert();
        $ApiOrderinsert->team = 'goldteamto';
        $ApiOrderinsert->value = 0;
        $ApiOrderinsert->created_by = 'Admin';
        $ApiOrderinsert->save();
        
        $ApiOrderinsert = new ApiOrderinsert();
        $ApiOrderinsert->team = 'greenteamfrom';
        $ApiOrderinsert->value = 0;
        $ApiOrderinsert->created_by = 'Admin';
        $ApiOrderinsert->save();

        $ApiOrderinsert = new ApiOrderinsert();
        $ApiOrderinsert->team = 'greenteamto';
        $ApiOrderinsert->value = 0;
        $ApiOrderinsert->created_by = 'Admin';
        $ApiOrderinsert->save();

        $ApiOrderinsert = new ApiOrderinsert();
        $ApiOrderinsert->team = 'pinkteamfrom';
        $ApiOrderinsert->value = 0;
        $ApiOrderinsert->created_by = 'Admin';
        $ApiOrderinsert->save();

        $ApiOrderinsert = new ApiOrderinsert();
        $ApiOrderinsert->team = 'pinkteamto';
        $ApiOrderinsert->value = 0;
        $ApiOrderinsert->created_by = 'Admin';
        $ApiOrderinsert->save();

        $ApiOrderinsert = new ApiOrderinsert();
        $ApiOrderinsert->team = 'purpleteamfrom';
        $ApiOrderinsert->value = 0;
        $ApiOrderinsert->created_by = 'Admin';
        $ApiOrderinsert->save();

        $ApiOrderinsert = new ApiOrderinsert();
        $ApiOrderinsert->team = 'purpleteamto';
        $ApiOrderinsert->value = 0;
        $ApiOrderinsert->created_by = 'Admin';
        $ApiOrderinsert->save();

        $ApiOrderinsert = new ApiOrderinsert();
        $ApiOrderinsert->team = 'orangeteamfrom';
        $ApiOrderinsert->value = 0;
        $ApiOrderinsert->created_by = 'Admin';
        $ApiOrderinsert->save();

        $ApiOrderinsert = new ApiOrderinsert();
        $ApiOrderinsert->team = 'orangeteamto';
        $ApiOrderinsert->value = 0;
        $ApiOrderinsert->created_by = 'Admin';
        $ApiOrderinsert->save();
    }
}