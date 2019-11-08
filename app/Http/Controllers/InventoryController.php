<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\ChryslerSurvey;
use App\FordSurvey;
use App\MazdaSurvey;
use App\Account;
use App\ScannerHistorys;
use Illuminate\Http\Request;
use \Milon\Barcode\DNS1D;
use SoapClient;
use App\ApiOrderinsert;
use Hkonnet\LaravelEbay\Facade\Ebay;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Shopping\Services;
use \Hkonnet\LaravelEbay\EbayServices;

class InventoryController extends Controller
{
  protected $str_hand = "";
  protected $str_tail = false;

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $user = auth()->user()->role;
    return view('admin.inventory.index', ['role' => $user]);
  }

  public function create(Request $request)
  {
    return view('admin.inventory.create');
  }

  public function setpublish()
  {
    $username = auth()->user()->username;
    $id = request('id');
    $inventory = Inventory::findOrfail($id);
    $inventory->publish = request('publish');
    $inventory->save();
    if (request('publish') == 1) {
      // ScannerHistorys::where('inventories_id', $id)->update(['action' => 'increase inventory']);
      ScannerHistorys::create([
        'inventories_id' => $id,
        'username' => $username,
        'action' => "increase inventory"
      ]);
    } else {
      // ScannerHistorys::where('inventories_id', $id)->update(['action' => 'decreased inventory']);
      ScannerHistorys::create([
        'inventories_id' => $id,
        'username' => $username,
        'action' => "decreased inventory"
      ]);
    }

    return response()->json($inventory);
  }

  public function addgeneric(Request $request)
  {
    $username = auth()->user()->username;

    $inventory = Inventory::create([
      'account_id' => $request->account_id,
      'xyzposition' => $request->xyzposition,
      'gxxorlsa' => $request->gxxorlsa,
      'hardware' => $request->hardwarecode,
      'software' => $request->softwarecode,
      'description' => $request->description,
      'publish' => 1,
    ]);
    $barcode = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG(strval($inventory->id), "C128") . '" alt="barcode" />';

    ScannerHistorys::create([
      'inventories_id' => $inventory->id,
      'username' => $username,
      'action' => "created inventory"
    ]);

    $inventory->title = $inventory->hardware
      . ' | '
      . $inventory->software
      . ' | '
      . $inventory->description
      . ' | '
      . $inventory->gxxorlsa
      . " | 'Stock S'"
      . $inventory->id;

    $inventory->save();

    return view('admin.print', [
      'barcode' => $barcode,
      'id' => $inventory->id,
      'hardware' => $inventory->hardware,
      'software' => $inventory->software,
      'description' => $inventory->description,
      'xyzposition' => $inventory->xyzposition
    ]);
  }

  public function addinventory(Request $request)
  {
    if ($request->ford_id !== null && $request->ford_id >= 0) {
      $ford = FordSurvey::where('id', $request->ford_id)->get()->first();
      $inventory = Inventory::create([
        'account_id' => $request->account_id,
        'xyzposition' => $request->xyzposition,
        'gxxorlsa' => $request->gxxorlsa,
        'hardware' => $ford->hardware,
        'catchcode' => $ford->catch_word,
        'partnumber' => $ford->part_number,
        'year' => $ford->model_year,
        'vehicle' => $ford->vehicle,
        'engine' => $ford->engine,
        'publish' => 1,
      ]);

      $inventory->title = $inventory->hardware
        . ' | '
        . $inventory->catchcode
        . ' | '
        . $inventory->partnumber
        . ' | '
        . $inventory->gxxorlsa
        . " | 'Stock S'"
        . $inventory->id;

      $inventory->save();

      return view('admin.print', [
        'id' => $inventory->id,
        'hardware' => $inventory->hardware,
        'software' => '',
        'description' => '',
        'xyzposition' => $request->xyzposition,
      ]);
    } elseif ($request->chrysler_id !== null && $request->chrysler_id >= 0) {
      $chrysler = ChryslerSurvey::where('id', $request->chrysler_id)->get()->first();
      $inventory = Inventory::create([
        'account_id' => $request->account_id,
        'xyzposition' => $request->xyzposition,
        'gxxorlsa' => $request->gxxorlsa,
        'controller' => $chrysler->controller,
        'moduletype' => $chrysler->module_type,
        'hardware' => $chrysler->hardware_pn,
        'software' => $chrysler->software_pn,
        'oldsoftware' => $chrysler->older_software_pn,
        'year' => $chrysler->year,
        'body' => $chrysler->body,
        'engine' => $chrysler->engine,
        'description' => $chrysler->software_description,
        'publish' => 1,
      ]);

      $inventory->title = $inventory->moduletype
        . ' | '
        . $inventory->hardware
        . ' | '
        . $inventory->software
        . ' | '
        . $inventory->oldsoftware
        . ' | '
        . $inventory->gxxorlsa
        . " | 'Stock S'"
        . $inventory->id;

      $inventory->save();

      return view('admin.print', [
        'id' => $inventory->id,
        'hardware' => $inventory->hardware,
        'software' => $inventory->software,
        'description' => $inventory->description,
        'xyzposition' => $inventory->xyzposition,
      ]);
    } elseif ($request->mazda_id !== null && $request->mazda_id >= 0) {
      $mazda = MazdaSurvey::where('id', $request->mazda_id)->get()->first();
      $inventory = Inventory::create([
        'account_id' => $request->account_id,
        'xyzposition' => $request->xyzposition,
        'gxxorlsa' => $request->gxxorlsa,
        'hardware' => $mazda->hardware,
        '_18881_pn' => $mazda->_18881_pn,
        'catch' => $mazda->catch,
        '_12a650' => $mazda->_12a650,
        'epc_pn' => $mazda->epc_pn,
        'mazda_model' => $mazda->mazda_model,
        'year' => $mazda->year,
        'engine' => $mazda->engine,
        'trans' => $mazda->trans,
        'emissions' => $mazda->emissions,
        'sec' => $mazda->sec,
        'description' => $mazda->description,
        'publish' => 1,
      ]);

      $inventory->title = $inventory->hardware
        . ' | '
        . $inventory->_18881_pn
        . ' | '
        . $inventory->_12a650
        . ' | '
        . $inventory->epc_pn
        . ' | '
        . $inventory->gxxorlsa
        . " | 'Stock S'"
        . $inventory->id;

      $inventory->save();

      return view('admin.print', [
        'id' => $inventory->id,
        'hardware' => $inventory->hardware,
        'software' => '',
        'description' => $inventory->description,
        'xyzposition' => $inventory->xyzposition,
      ]);
    }

    return back()->with('success', 'failed');
  }

  public function partmatches(Request $request)
  {
    $searchTerm = request('q');

    if ($searchTerm === null) {
      return back()->with('success', 'Please input terms for search');
    }

    $chryslers = ChryslerSurvey::search([
      'controller',
      'module_type',
      'hardware_pn',
      'software_pn',
      'older_software_pn',
      'year',
      'body',
      'engine',
      'software_description'
    ], $searchTerm)->get();

    foreach ($chryslers as $chrysler) {
      $inv = Inventory::select('xyzposition')
        ->where([
          'hardware' => $chrysler['hardware_pn'],
        ])
        ->orderBy('id', 'desc')
        ->get();
      if (count($inv)) {
        $chrysler['xyzposition'] = $inv[0]->xyzposition;
      }
    }

    $fords = FordSurvey::search([
      'hardware',
      'catch_word',
      'part_number',
      'model_year',
      'vehicle',
      'engine'
    ], $searchTerm)->get();

    foreach ($fords as $ford) {
      $inv = Inventory::select('xyzposition')
        ->where([
          'hardware' => $ford['hardware'],
        ])
        ->orderBy('id', 'desc')
        ->get();
      if (count($inv)) {
        $ford['xyzposition'] = $inv[0]->xyzposition;
      }
    }

    $mazdas = MazdaSurvey::search([
      'hardware',
      '_18881_pn',
      'catch',
      '_12a650',
      'epc_pn',
      'mazda_model',
      'year',
      'engine',
      'trans',
      'emissions',
      'sec',
      'description',
      'vin_ex'
    ], $searchTerm)->get();

    foreach ($mazdas as $mazda) {
      $inv = Inventory::select('xyzposition')
        ->where([
          'hardware' => $mazda['hardware'],
        ])
        ->orderBy('id', 'desc')
        ->get();
      if (count($inv)) {
        $mazda['xyzposition'] = $inv[0]->xyzposition;
      }
    }

    return view('admin/inventory/misc/partmatches', [
      'chryslers' => $chryslers,
      'fords' => $fords,
      'mazdas' => $mazdas,
      'accounthnumber' => $request->accounthnumber,
      'gxxorlsa' => $request->gxxorlsa,
    ]);
  }

  public function bulkcheckinventory()
  {
    return view('admin/inventory/bulkcheckinventory');
  }

  public function bulkaddaccounts()
  {
    return view('admin/inventory/bulkaddaccounts', ['bulkinfos' => []]);
  }

  public function ebayandwebsitebreakdown()
  {
    return view('admin/inventory/ebayandwebsitebreakdown');
  }

  public function addcore()
  {
    return view('admin/inventory/addcore');
  }

  public function strtok_l($tok, $str = null)
  {
    if ($str !== null) {
      $this->str_hand = $str;
    }

    if ($this->str_hand === "") {

      if ($this->str_tail === true) {
        $this->str_tail = false;
        return "";
      }
      return false;
    }

    $pos = \strpos($this->str_hand, $tok);

    if ($pos === false) {
      $tok = $this->str_hand;
      $this->str_hand = "";
    } else {
      $this->str_tail = true;

      if ($pos <= 0) {
        $tok = "";
        $pos = 0;
      } else {
        $tok = \substr($this->str_hand, 0, $pos);
      }
      $this->str_hand = \substr($this->str_hand, $pos + 1);
    }

    return $tok;
  }

  public function bulkInsertInfo(Request $request)
  {
    $bulkInfos = $request->input('bulk-info');
    $addInfos = [];
    $addInfo = \strtok($bulkInfos, "\r\n");
    $word = 'eBay- fs1inc';

    while ($addInfo !== false) {
      if (strpos($addInfo, $word) !== false) {
        $info = [
          'record_number' => $this->strtok_l("\t", $addInfo),
          'record_owner' => $this->strtok_l("\t"),
          'order_type' => $this->strtok_l("\t"),
          'ebay_id' => $this->strtok_l("\t"),
          'ebayorder_id' => $this->strtok_l("\t"),
          'account_name' => $this->strtok_l("\t"),
          'phone' => $this->strtok_l("\t"),
          'customer_email' => $this->strtok_l("\t"),
          'item_purchased' => $this->strtok_l("\t"),
          'customer_vin' => $this->strtok_l("\t"),
          'price_paid' => $this->strtok_l("\t"),
          'date_purchased' => $this->strtok_l("\t"),
          'customer_street' => $this->strtok_l("\t"),
          'ship_address_info' => $this->strtok_l("\t"),
          'customer_city' => $this->strtok_l("\t"),
          'customer_state' => $this->strtok_l("\t"),
          'customer_zipcode' => $this->strtok_l("\t"),
          'customer_country' => $this->strtok_l("\t"),
        ];
      } else {
        $info = [
          'record_owner' => $this->strtok_l("\t", $addInfo),
          'record_number' => $this->strtok_l("\t"),
          'magento_id' => $this->strtok_l("\t"),
          'order_type' => $this->strtok_l("\t"),
          'date_purchased' => $this->strtok_l("\t"),
          'account_name' => $this->strtok_l("\t"),
          'price_paid' => $this->strtok_l("\t"),
          'customer_email' => $this->strtok_l("\t"),
          'phone' => $this->strtok_l("\t"),
          'item_purchased' => $this->strtok_l("\t"),
          'customer_vin' => $this->strtok_l("\t"),
          'ship_to_name' => $this->strtok_l("\t"),
          'customer_street' => $this->strtok_l("\t"),
          'ship_address_info' => $this->strtok_l("\t"),
          'customer_city' => $this->strtok_l("\t"),
          'customer_state' => $this->strtok_l("\t"),
          'customer_zipcode' => $this->strtok_l("\t"),
          'customer_country' => $this->strtok_l("\t"),
        ];
      }

      if (strpos($addInfo, $word) !== false) {
        $account = Account::create([
          'accountname' => $info['account_name'],
          'accountteam' => $info['record_owner'],
          'ordertype' => $info['order_type'],
          'ebayorder_id' => $info['ebayorder_id'],
          'itempurchased' => $info['item_purchased'],
          'customervin' => $info['customer_vin'],
          'skuprice' => $info['price_paid'], // assign the inputed price_paid to skuprice
          'ebayusername' => isset($info['ebay_id']) ? $info['ebay_id'] : '',
          'salesrecord' => $info['record_number'],
          'datecustomerpurchased' => date("Y-m-d h:i:s", strtotime($info['date_purchased'])),
          'created_by' => auth()->user()->username,
        ]);
      } else {
        $account = Account::create([
          'accountname' => $info['account_name'],
          'accountteam' => $info['record_owner'],
          'magento_id' => $info['magento_id'],
          'ordertype' => $info['order_type'],
          'itempurchased' => $info['item_purchased'],
          'customervin' => $info['customer_vin'],
          'skuprice' => $info['price_paid'], // assign the inputed price_paid to skuprice
          'ebayusername' => isset($info['ebay_id']) ? $info['ebay_id'] : '',
          'salesrecord' => $info['record_number'],
          'datecustomerpurchased' => date("Y-m-d h:i:s", strtotime($info['date_purchased'])),
          'created_by' => auth()->user()->username,
        ]);
      }
      $phone = str_replace('-', '', $info['phone']);
      $phone = str_replace('(', '', $phone);
      $phone = str_replace(')', '', $phone);
      $phone = str_replace(' ', '', $phone);

      if (strpos($addInfo, $word) !== false) {
        $account->addCustomerMetaData([
          'shopname' => $info['ship_address_info'],
          'street1' => $info['customer_street'],
          'city' => $info['customer_city'],
          'state' => $info['customer_state'],
          'zip' => $info['customer_zipcode'],
          'country' => $info['customer_country'],
          'phone' => $phone,
          'email' => $info['customer_email']
        ]);
      } else {
        $account->addCustomerMetaData([
          'shipto' => $info['ship_to_name'],
          'shopname' => $info['ship_address_info'],
          'street1' => $info['customer_street'],
          'city' => $info['customer_city'],
          'state' => $info['customer_state'],
          'zip' => $info['customer_zipcode'],
          'country' => $info['customer_country'],
          'phone' => $phone,
          'email' => $info['customer_email']
        ]);
      }

      array_push($addInfos, $info);
      $addInfo = \strtok("\r\n");
    }

    return view('admin/inventory/bulkaddaccounts', ['bulkinfos' => $addInfos]);
  }

  public function bulkcheckinventorycache(Request $request)
  {
    $bulkInfos = request('bulk-info');
    $info = \strtok($bulkInfos, "\r\n");
    $checkResult = [];
    while ($info !== false) {
      $hNumber = str_replace("h", "", trim(strtolower($info)));

      $account = Account::find(intval($hNumber));
      if (!empty($account) && $account->pcmhardwaretype !== '') {
        $instock = Inventory::where([
          'hardware' => $account->pcmhardwaretype,
          'publish' => '1',
        ])->first();

        $stock = isset($instock);
        $returned = !isset($instock);
        $stockNumber = 'N/A';
        $xyzPosition = '';

        if (!empty($instock)) {
          $stockNumber = $instock->id;
          $xyzPosition = $instock->xyzposition;
        }

        array_push($checkResult, [
          'accountid'       => $account->id,
          'team'            => $account->accountteam,
          'hnumber'         => 'H' . $hNumber,
          'pcmhardwaretype' => $account->pcmhardwaretype,
          'stock'           => $stock,
          'returned'        => $returned,
          'stocknumber'     => $stockNumber,
          'xyzposition'     => $xyzPosition,
        ]);
      }

      $info = \strtok("\r\n");
    }
    // dd($checkResult);
    return view('admin.inventory.bulkcheckinventory', ['checked' => $checkResult]);
  }

  public function FetchOrderInfo()
  {
    $latestSalesRecord = Account::select('salesrecord')
      ->where('ordertype', 'Website')
      ->whereRaw('LENGTH(salesrecord) = 9')
      ->whereRaw("concat('', salesrecord * 1) = salesrecord")
      ->orderBy('id', 'desc')
      ->first();

    $client = new SoapClient('https://www.fs1inc.com/index.php/api/v2_soap?wsdl=1');

    $session = $client->login('soapapi', 'oM4GNiWcL6');
    $filter = [
      'filter' => [],
      'complex_filter' => [
        [
          'key' => 'increment_id',
          'value' => [
            'key' => 'gt',
            'value' => '100051669',
          ],
        ],
      ],
    ];

    $result = $client->salesOrderList($session, $filter);
    if (count($result)) {
      $newOrders = [];
      $count = 0;
      foreach ($result as $item) {
        $orderInfo = $client->salesOrderInfo($session, ((object) $item)->increment_id);
        $objOrderInfo = (object) $orderInfo;

        $findAccount = Account::where('salesrecord', $objOrderInfo->increment_id)->count();
        if ($findAccount == 0) {
          $accountteam = '';
          $salesrecord = substr($objOrderInfo->increment_id, -2);
          $ApiOrder = ApiOrderinsert::where('valuefrom', '<=', $salesrecord)->where('valueto', '>=', $salesrecord)->first();
          if ($ApiOrder)
            $accountteam = $ApiOrder->title;

          array_push($newOrders, [
            'account_name'      => $objOrderInfo->customer_firstname . ' ' . $objOrderInfo->customer_lastname,
            // 'record_owner'      => auth()->user()->username,
            'order_type'        => 'Website',
            'accountteam'        => $accountteam,
            'magento_id'        => $objOrderInfo->order_id,
            'item_purchased'    => ((object) $objOrderInfo->items[0])->name,
            'price_paid'        => $objOrderInfo->grand_total,
            'record_number'     => $objOrderInfo->increment_id,
            'date_purchased'    => $objOrderInfo->created_at,
            'customer_email'    => $objOrderInfo->customer_email,
            'customer_phone'    => $objOrderInfo->shipping_address ? ((object) $objOrderInfo->shipping_address)->telephone : '',
            'customer_street'   => $objOrderInfo->shipping_address ? ((object) $objOrderInfo->shipping_address)->street : '',
            'customer_city'     => $objOrderInfo->shipping_address ? ((object) $objOrderInfo->shipping_address)->city : '',
            'customer_state'    => $objOrderInfo->shipping_address->region ? ((object) $objOrderInfo->shipping_address)->region : '',
            'customer_country'  => $objOrderInfo->shipping_address ? ((object) $objOrderInfo->shipping_address)->country_id : '',
            'customer_zipcode'  => $objOrderInfo->shipping_address ? ((object) $objOrderInfo->shipping_address)->postcode : '',
            'ship_to_name'      => $objOrderInfo->customer_firstname . ' ' . $objOrderInfo->customer_lastname,
          ]);
          $count++;
          if ($count == 100)
            break;
        }
      }
      return response()->json([
        'success' => true,
        'message' => 'Succeed to fetch ' . count($newOrders) . ' result from fs1inc.',
        'data'    => $newOrders,
      ]);
    }

    return response()->json([
      'success' => false,
      'message' => 'Faild to fetch from fs1inc.',
      'data'    => [],
    ]);
  }

  public function FetchEbayOrderInfo()
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
    $response = $service->getOrders($getOrders);

    if (count($response->OrderArray->Order)) {
      $newOrders = [];
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

        $findAccount = Account::where('salesrecord', $record_number)->count();
        if ($findAccount == 0) {
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
          if ($order->TransactionArray->Transaction[0]->Buyer->Email)
            $customer_email =  $order->TransactionArray->Transaction[0]->Buyer->Email;

          $accountteam = '';
          $salesrecord = substr($record_number, -2);
          $ApiOrder = ApiOrderinsert::where('valuefrom', '<=', $salesrecord)->where('valueto', '>=', $salesrecord)->first();
          if ($ApiOrder)
            $accountteam = $ApiOrder->title;

          array_push($newOrders, [
            'record_number' => $record_number,
            'account_name' => $account_name,
            'accountteam' => $accountteam,
            'order_type' => $order_type,
            'ebayusername' => $ebayusername,
            'ebayorder_id' => $ebayorder_id,
            'ebay_id' => '',
            'item_purchased' => $item_purchased,
            'customervin' => $customer_vin,
            'price_paid' => $price_paid,
            'salesrecord' => $record_number,
            'created_at' => date("Y-m-d h:i:s"),
            'created_by' => '',
            'ship_to_name' => $ship_address_info,
            'customer_street' => $customer_street,
            'customer_city' => $customer_city,
            'customer_state' => $customer_state,
            'customer_zipcode' => $customer_zipcode,
            'customer_country' => $customer_country,
            'customer_phone' => $phone,
            'customer_email' => $customer_email
          ]);
          // printf("SaleRecordID %s\n", $order->ShippingDetails->SellingManagerSalesRecordNumber);
        }
      }
      return response()->json([
        'success' => true,
        'message' => 'Succeed to fetch ' . count($newOrders) . ' result from ebay.',
        'data'    => $newOrders,
      ]);
    }
    return response()->json([
      'success' => false,
      'message' => 'Faild to fetch from ebay.',
      'data'    => [],
    ]);
  }

  public function checkMagentoOrders(Request $request)
  {
    $orders = $request->input('orders');
    $bulkInfo = [];
    $row = \strtok($orders, "\r\n");

    while ($row !== false) {
      if (strpos($row, 'eBay- fs1inc') !== false) {
        return response()->json([
          'success' => false,
          'message' => 'Invalid order information.',
          'data'    => 'False',
        ], 200);
      } else {
        array_push($bulkInfo, [
          'record_owner' => $this->strtok_l("\t", $row),
          'record_number' => $this->strtok_l("\t"),
          'magento_id' => $this->strtok_l("\t"),
          'order_type' => $this->strtok_l("\t"),
          'date_purchased' => $this->strtok_l("\t"),
          'account_name' => $this->strtok_l("\t"),
          'price_paid' => $this->strtok_l("\t"),
          'customer_email' => $this->strtok_l("\t"),
          'customer_phone' => $this->strtok_l("\t"),
          'item_purchased' => $this->strtok_l("\t"),
          'customer_vin' => $this->strtok_l("\t"),
          'ship_to_name' => $this->strtok_l("\t"),
          'customer_street' => $this->strtok_l("\t"),
          'ship_address_info' => $this->strtok_l("\t"),
          'customer_city' => $this->strtok_l("\t"),
          'customer_state' => $this->strtok_l("\t"),
          'customer_zipcode' => $this->strtok_l("\t"),
          'customer_country' => $this->strtok_l("\t"),
        ]);
        $row = \strtok("\r\n");
      }
    }
    return response()->json([
      'success' => true,
      'message' => 'You could add the bulk accounts into inventory.',
      'data'    => $bulkInfo,
    ], 200);
  }

  public function checkEbayOrders(Request $request)
  {
    $orders = $request->input('orders');
    $bulkInfo = [];
    $row = \strtok($orders, "\r\n");

    while ($row !== false) {
      if (strpos($row, 'eBay- fs1inc') !== false) {
        array_push($bulkInfo, [
          'record_number' => $this->strtok_l("\t", $row),
          'record_owner' => $this->strtok_l("\t"),
          'order_type' => $this->strtok_l("\t"),
          'ebay_id' => $this->strtok_l("\t"),
          'ebayorder_id' => $this->strtok_l("\t"),
          'account_name' => $this->strtok_l("\t"),
          'customer_phone' => $this->strtok_l("\t"),
          'customer_email' => $this->strtok_l("\t"),
          'item_purchased' => $this->strtok_l("\t"),
          'customer_vin' => $this->strtok_l("\t"),
          'price_paid' => $this->strtok_l("\t"),
          'date_purchased' => $this->strtok_l("\t"),
          'customer_street' => $this->strtok_l("\t"),
          'ship_address_info' => $this->strtok_l("\t"),
          'customer_city' => $this->strtok_l("\t"),
          'customer_state' => $this->strtok_l("\t"),
          'customer_zipcode' => $this->strtok_l("\t"),
          'customer_country' => $this->strtok_l("\t"),
        ]);
        $row = \strtok("\r\n");
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Invalid order information.',
          'data'    => 'False',
        ], 200);
      }
    }

    return response()->json([
      'success' => true,
      'message' => 'You could add the bulk accounts into iventory.',
      'data'    => $bulkInfo,
    ], 200);
  }

  public function addMagentoOrders(Request $request)
  {
    $bulkInfo = $request->input('bulkInfo');

    foreach ($bulkInfo as $item) {

      $account = Account::create([
        'accountname' => $item['account_name'],
        'accountteam' => $item['record_owner'],
        'magento_id' => $item['magento_id'],
        'ordertype' => $item['order_type'],
        'itempurchased' => $item['item_purchased'],
        'customervin' => $item['customer_vin'],
        'skuprice' => $item['price_paid'], // assign the inputed price_paid to skuprice
        'salesrecord' => $item['record_number'],
        'datecustomerpurchased' => date("Y-m-d h:i:s", strtotime($item['date_purchased'])),
        'created_by' => auth()->user()->username,
      ]);

      $phone = str_replace('-', '', $item['customer_phone']);
      $phone = str_replace('(', '', $phone);
      $phone = str_replace(')', '', $phone);
      $phone = str_replace(' ', '', $phone);

      $account->addCustomerMetaData([
        'shipto' => $item['ship_to_name'],
        'street1' => $item['customer_street'],
        'city' => $item['customer_city'],
        'state' => $item['customer_state'],
        'zip' => $item['customer_zipcode'],
        'country' => $item['customer_country'],
        'phone' => $phone,
        'email' => $item['customer_email']
      ]);
    }

    return response()->json([
      'success' => true,
      'message' => 'Added bulk accounts successfully.',
    ]);
  }

  public function addEbayOrders(Request $request)
  {
    $bulkInfo = $request->input('bulkInfo');

    foreach ($bulkInfo as $item) {
      $account = Account::create([
        'accountname' => $item['account_name'],
        'accountteam' => $item['record_owner'],
        'ordertype' => $item['order_type'],
        'ebayorder_id' => $item['ebayorder_id'],
        'itempurchased' => $item['item_purchased'],
        'customervin' => $item['customer_vin'],
        'skuprice' => $item['price_paid'], // assign the inputed price_paid to skuprice
        'ebayusername' => isset($item['ebay_id']) ? $item['ebay_id'] : '',
        'salesrecord' => $item['record_number'],
        'datecustomerpurchased' => date("Y-m-d h:i:s"),
        'created_by' => auth()->user()->username,
      ]);

      $phone = str_replace('-', '', $item['customer_phone']);
      $phone = str_replace('(', '', $phone);
      $phone = str_replace(')', '', $phone);
      $phone = str_replace(' ', '', $phone);

      $account->addCustomerMetaData([
        'shipto' => $item['ship_address_info'],
        'street1' => $item['customer_street'],
        'city' => $item['customer_city'],
        'state' => $item['customer_state'],
        'zip' => $item['customer_zipcode'],
        'country' => $item['customer_country'],
        'phone' => $phone,
        'email' => $item['customer_email']
      ]);
    }

    return response()->json([
      'success' => true,
      'message' => 'Added bulk accounts successfully.',
    ]);
  }

  public function addEbayOrdersLive(Request $request)
  {
    $bulkInfo = $request->input('bulkInfo');

    foreach ($bulkInfo as $item) {
      $account = Account::create([
        'accountname' => $item['account_name'],
        'accountteam' => $item['accountteam'],
        'ordertype' => $item['order_type'],
        'ebayorder_id' => $item['ebayorder_id'],
        'itempurchased' => $item['item_purchased'],
        // 'customervin' => $item['customervin'],
        'skuprice' => $item['price_paid'], // assign the inputed price_paid to skuprice
        'ebayusername' => $item['ebayusername'],
        'salesrecord' => $item['salesrecord'],
        'datecustomerpurchased' => date("Y-m-d h:i:s", strtotime($item['created_at'])),
        'created_by' => auth()->user()->username,
      ]);

      $account->addCustomerMetaData([
        'shipto' => $item['ship_to_name'],
        'shopname' => $item['ship_to_name'],
        'street1' => $item['customer_street'],
        'city' => $item['customer_city'],
        'state' => $item['customer_state'],
        'zip' => $item['customer_zipcode'],
        'country' => $item['customer_country'],
        'phone' => $item['customer_phone'],
        'email' => $item['customer_email']
      ]);
    }

    return response()->json([
      'success' => true,
      'message' => 'Added bulk ebay accounts successfully.',
    ]);
  }

  public function dataTableInventory()
  {
    $Inventory = Inventory::get();
    $array = [];
    foreach ($Inventory as $Invent) {
      $data = array(
        'id' => $Invent->id,
        'account_id' => $Invent->account_id,
        'title' => $Invent->title,
        'hardware' => $Invent->hardware,
        'software' => $Invent->software,
        'xyzposition' => $Invent->xyzposition,
        'gxxorlsa' => $Invent->gxxorlsa,
        'description' => $Invent->description,
      );
      array_push($array, $data);
    }
    return json_encode($array);
  }

  public function printPreview(Request $request)
  {
    $print_table = $request->print_table;
    // dd($print_table);
    return view('admin.inventory.bulkcheckprintview', ['checked' => $print_table]);
  }
}
