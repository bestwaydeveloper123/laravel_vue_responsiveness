<?php

use Illuminate\Database\Seeder;
use App\Pcmhwtesting;

class PcmhwtestingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Pcmhwtesting = new Pcmhwtesting();
        $Pcmhwtesting->pcmhw = 'dpc-202';
        $Pcmhwtesting->save();
    }
}
