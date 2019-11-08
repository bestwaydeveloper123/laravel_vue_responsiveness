<?php

use Illuminate\Database\Seeder;
use App\AppOption;

class AppOptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $AppOption = new AppOption();
        $AppOption->name = 'Clean And Wrap';
        $AppOption->points = 6;
        $AppOption->save();
        
        $AppOption = new AppOption();
        $AppOption->name = 'Pack';
        $AppOption->points = 3;
        $AppOption->save();
        
        $AppOption = new AppOption();
        $AppOption->name = 'Grind';
        $AppOption->points = 6;
        $AppOption->save();
        
        $AppOption = new AppOption();
        $AppOption->name = 'Pre Opened USPS';
        $AppOption->points = 2;
        $AppOption->save();
        
        $AppOption = new AppOption();
        $AppOption->name = 'UPS RMA';
        $AppOption->points = 1;
        $AppOption->save();
        
        $AppOption = new AppOption();
        $AppOption->name = 'Morning Picture Label';
        $AppOption->points = 0;
        $AppOption->save();
        
        $AppOption = new AppOption();
        $AppOption->name = "Other RMA's With Pick Up and Clean Up";
        $AppOption->points = 3;
        $AppOption->save();
        
        $AppOption = new AppOption();
        $AppOption->name = 'Clean and Tag';
        $AppOption->points = 3;
        $AppOption->save();
        
        $AppOption = new AppOption();
        $AppOption->name = 'Morning Pictures Excluding Label';
        $AppOption->points = 6;
        $AppOption->save();
        
        $AppOption = new AppOption();
        $AppOption->name = 'Picture';
        $AppOption->points = 1;
        $AppOption->save();
        
        $AppOption = new AppOption();
        $AppOption->name = 'RMA With Pick Up and Clean Up';
        $AppOption->points = 3;
        $AppOption->save();
    }
}
