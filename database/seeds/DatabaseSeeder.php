<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(RankTableSeeder::class);
        $this->call(AppOptionTableSeeder::class);
        $this->call(AppversionsTableSeeder::class);
        $this->call(PcmhwtestingTableSeeder::class);
        $this->call(StockMastersTableSeeder::class);
        $this->call(ApiOrderinsertTableSeeder::class);
    }
}
