<?php

use Illuminate\Database\Seeder;
use App\StockMaster;

class StockMastersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $StockMaster = new StockMaster();
        $StockMaster->part_ype = "Items you can spend up to 40% of purchase price: dpc's (f-series only) , 2005, 2006 Wrangler, Cummins ECM's"; 
        $StockMaster->save();

        $StockMaster = new StockMaster();
        $StockMaster->part_ype = "Items you can spend up to 30% of purchase price: 2005-2006 escape/mariner.tribute (3.0l only)"; 
        $StockMaster->save();

        $StockMaster = new StockMaster();
        $StockMaster->part_ype = "25% 1996-2001 Jtecs, 2007 ford edge, 2008 explorer,&nbsp; PT cruiser turbo ECM's, 2003-2007 Cadillac 2.8L and 3.6L"; 
        $StockMaster->save();

        $StockMaster = new StockMaster();
        $StockMaster->part_ype = "Items you can spend up to 15% of purchase price: anything else that we have listed"; 
        $StockMaster->save();
    }
}
