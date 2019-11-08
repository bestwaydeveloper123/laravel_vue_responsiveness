<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Vendor;
use App\TrackingStatus;
use Exception;

\EasyPost\EasyPost::setApiKey(getenv('EASYPOST_API_KEY'));

class AutoRefund extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AutoRefund';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \EasyPost\EasyPost::setApiKey(getenv('EASYPOST_API_KEY'));
        //current time
        date_default_timezone_set('America/New_York');
        $FiftineDays = date('Y-m-d', strtotime("-15 days"));

        $TrackingStatus = TrackingStatus::with('EasypostWebhook')->whereHas('EasypostWebhook', function ($qr) {
            $qr->where('pretransit', 'pre_transit')
                ->where(function ($query) {
                    return $query->where('intransit', null)->orWhere('intransit', '');
                });
        })->whereDate('label_creation_date', '<', $FiftineDays)->where('refunded', null)->get();

        foreach ($TrackingStatus as $Vendor) {
            $carrier = $Vendor["carrier"];
            $tracking_codes = $Vendor["trackingcode"];

            try {
                \EasyPost\Refund::create(array(
                    "carrier" => "$carrier",
                    "tracking_codes" => "$tracking_codes"
                ));
                // $all = \EasyPost\Refund::all();
                // $sqlrefund = "update ignore vendors set refunded='refunded' where trackingcode='$tracking_codes'";
                $TrackingUpdate = TrackingStatus::where('trackingcode', $tracking_codes)->first();
                $TrackingUpdate->refunded = 'refunded';
                $TrackingUpdate->save();
            } catch (\EasyPost\Error $e) {
                continue;
            }
        }

        $Vendors = Vendor::with('EasypostWebhook')->whereHas('EasypostWebhook', function ($qr) {
            $qr->where('pretransit', 'pre_transit')
                ->where(function ($query) {
                    return $query->where('intransit', null)->orWhere('intransit', '');
                });
        })->whereDate('label_creation_date', '<', $FiftineDays)->where('refunded', null)->get();

        foreach ($Vendors as $Vendor) {
            $carrier = $Vendor["carrier"];
            $tracking_codes = $Vendor["trackingcode"];

            try {
                \EasyPost\Refund::create(array(
                    "carrier" => "$carrier",
                    "tracking_codes" => "$tracking_codes"
                ));
                // $all = \EasyPost\Refund::all();
                // $sqlrefund = "update ignore vendors set refunded='refunded' where trackingcode='$tracking_codes'";
                $VendorUpdate = Vendor::where('trackingcode', $tracking_codes)->first();
                $VendorUpdate->refunded = 'refunded';
                $VendorUpdate->save();
            } catch (\EasyPost\Error $e) {
                continue;
            }
        }
    }
}
