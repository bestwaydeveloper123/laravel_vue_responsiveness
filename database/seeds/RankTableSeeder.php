<?php

use Illuminate\Database\Seeder;
use App\Rank;

class RankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rank_user = new Rank();
        $rank_user->name = 'teamleader';
        $rank_user->description = 'A Team Leader Rank';
        $rank_user->save();

        $rank_user = new Rank();
        $rank_user->name = 'senior';
        $rank_user->description = 'A Senior Rank';
        $rank_user->save();

        $rank_user = new Rank();
        $rank_user->name = 'junior';
        $rank_user->description = 'A Junior Rank';
        $rank_user->save();
    }
}
