<?php

use Illuminate\Database\Seeder;
use App\Appversion;

class AppversionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Appversion = new Appversion();
        $Appversion->name = 'android';
        $Appversion->version = '4.0';
        $Appversion->save();

        $Appversion = new Appversion();
        $Appversion->name = 'ios';
        $Appversion->version = '1.0';
        $Appversion->save();
    }
}
