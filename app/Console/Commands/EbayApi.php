<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Account;
use Hkonnet\LaravelEbay\Facade\Ebay;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Shopping\Services;
use \Hkonnet\LaravelEbay\EbayServices;

class EbayApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:EbayApi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ebay API cron job: auto account add from ebay API';

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
        /**
         * Create the service object.
         */
        $ebay_service = new EbayServices();
        $service = $ebay_service->createTrading();

        /**
         * Create the request object.
         */
        $request = new Types\GetMyeBaySellingRequestType();

        /**
         * An user token is required when using the Trading service.
         */
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $authToken = Ebay::getAuthToken();
        $request->RequesterCredentials->eBayAuthToken = $authToken;

        $CreateTimeFrom = new \DateTime();
        $CreateTimeFrom->add(\DateInterval::createFromDateString('yesterday'));
        $CreateTimeTo = new \DateTime();

        $args = array(
            "OrderStatus"   => "All",
            "SortingOrder"  => "Ascending",
            //"OrderRole"     => "Seller",
            "CreateTimeFrom"   => $CreateTimeFrom,
            "CreateTimeTo"   => $CreateTimeTo,
        );
        $getOrders = new Types\GetOrdersRequestType($args);
        $getOrders->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $getOrders->RequesterCredentials->eBayAuthToken = $authToken;
        $getOrders->IncludeFinalValueFee = true;
        // $getOrders->Pagination = new Types\PaginationType();
        // $getOrders->Pagination->EntriesPerPage = 3;
        //$getOrders->OrderIDArray = new Types\OrderIDArrayType();
        // $getOrdersPageNum = 1;

        //$getOrders->OrderIDArray->OrderID[] = '200980916385-1185594371010'; //'200980916385-1185594371010'
        $response = $service->getOrders($getOrders);
        // echo print_r($response, 1);
        $count = 0;

        foreach ($response->OrderArray->Order as $order) {
            $record_number = '';
            $account_name = '';
            $record_owner = '';
            $order_type = 'eBay- fs1inc';
            $ebayusername = '';
            $ebayorder_id = '';
            $item_purchased = '';
            $customer_vin = '';
            $price_paid = '';
            $ebay_id = '';

            if ($order->ShippingDetails->SellingManagerSalesRecordNumber)
                $record_number = $order->ShippingDetails->SellingManagerSalesRecordNumber;
            if ($order->ShippingAddress->Name)
                $account_name = $order->ShippingAddress->Name;
            if ($order->BuyerUserID)
                $ebayusername = $order->BuyerUserID;
            if ($order->OrderID)
                $ebayorder_id = $order->OrderID;
            if ($order->TransactionArray->Transaction[0]->Item->Title)
                $item_purchased = $order->TransactionArray->Transaction[0]->Item->Title;
            if ($order->Total->value)
                $price_paid = $order->Total->value;
            if ($order->OrderID)
                $ebay_id = $order->OrderID;

            $ship_address_info =  '';
            $customer_street =  '';
            $customer_city =  '';
            $customer_state =  '';
            $customer_zipcode =  '';
            $customer_country =  '';
            $phone =  '';
            $customer_email =  '';

            if ($order->ShippingAddress->Name)
                $ship_address_info =  $order->ShippingAddress->Name;
            if ($order->ShippingAddress->Street1)
                $customer_street =  $order->ShippingAddress->Street1 . ' ' . $order->ShippingAddress->Street2;
            if ($order->ShippingAddress->CityName)
                $customer_city =  $order->ShippingAddress->CityName;
            if ($order->ShippingAddress->StateOrProvince)
                $customer_state =  $order->ShippingAddress->StateOrProvince;
            if ($order->ShippingAddress->PostalCode)
                $customer_zipcode =  $order->ShippingAddress->PostalCode;
            if ($order->ShippingAddress->Country)
                $customer_country =  $order->ShippingAddress->Country;
            if ($order->ShippingAddress->Phone)
                $phone =  $order->ShippingAddress->Phone;
            if ($order->SellerEmail)
                $customer_email =  $order->SellerEmail;

            $account = Account::create([
                'accountname' => $account_name,
                'accountteam' => $record_owner,
                'ordertype' => $order_type,
                'ebayusername' => $ebayusername,
                'ebayorder_id' => $ebayorder_id,
                'itempurchased' => $item_purchased,
                'customervin' => $customer_vin,
                'skuprice' => $price_paid,
                // 'ebayusername' => $ebay_id,
                'salesrecord' => '',
                'datecustomerpurchased' => date("Y-m-d h:i:s"),
                'created_by' => 'cron',
            ]);
            if ($account) {
                $account->addCustomerMetaData([
                    'shopname' => $ship_address_info,
                    'street1' => $customer_street,
                    'city' => $customer_city,
                    'state' => $customer_state,
                    'zip' => $customer_zipcode,
                    'country' => $customer_country,
                    'phone' => $phone,
                    'email' => $customer_email
                ]);
            }
            // printf("SaleRecordID %s\n", $order->ShippingDetails->SellingManagerSalesRecordNumber);
            $count = $count + 1;
        }
        echo 'Record added: ' . $count;
    }
}
