<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\AppOption;
use App\UserAppOption;
use App\Account;
use App\Inventory;
use App\Core;
use App\Appversion;
use App\OrderStatus;
use App\Pcmhwtesting;
use App\TrackingStatus;
use App\AccountManagerPoint;
use App\StockMaster;
use App\ApiOrderinsert;
use App\Http\Resources\TrackingStatusResource;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use File;
use function GuzzleHttp\json_decode;
use App\Vendor;
use App\Http\Resources\VendorResource;
use App\EasypostWebhook;
use App\RmaForms;
use App\DocusignForms;
use \DTS\eBaySDK\Shopping\Services;
use Hkonnet\LaravelEbay\Facade\Ebay;
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;
use \Hkonnet\LaravelEbay\EbayServices;
use Mail;
use Illuminate\Support\Str;
use PDF;
use Storage;
use App\CustomerMetaData;
use App\ShippingTeamLeaderPoint;

\EasyPost\EasyPost::setApiKey(getenv('EASYPOST_API_KEY'));

class UserController extends Controller
{
  protected $perpage = 10;

  public function login(Request $request)
  {
    $email = $request->email;
    $password = $request->password;
    $user = User::where('email', $email)->first();
    if ($user) {
      if (Hash::check($password, $user->password)) {
        return response()->json([
          'IsSuccess' => true,
          'Message' => 'Successfully login.',
          'TotalCount' => 1,
          'Data' => $user
        ], 200);
      } else {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Incorrect email or password.',
          'TotalCount' => 0,
          'Data' => null
        ], 200);
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Incorrect email or password.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function getOptions()
  {
    $AppOption = AppOption::get();
    return response()->json([
      'IsSuccess' => true,
      'Message' => 'Successfully get options.',
      'TotalCount' => count($AppOption),
      'Data' => $AppOption
    ], 200);
  }

  public function scanBarcode(Request $request)
  {
    if (isset($request->code) && $request->code != '') {
      // $code =  substr($request->code, 0, -1);
      $code = $request->code;
      $Account = Account::find($code);
      if ($Account) {
        return response()->json([
          'IsSuccess' => true,
          'Message' => 'Verify barcode successfully.',
          'TotalCount' => 1,
          'Data' => ['account_id' => $Account->id]
        ], 200);
      } else {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Incorrect barcode.',
          'TotalCount' => 0,
          'Data' => null
        ], 200);
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Incorrect barcode.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function checkSID(Request $request)
  {
    if (isset($request->sid) && $request->sid != '') {
      $sid = $request->sid;
      $Inventory = Inventory::find($sid);
      if ($Inventory) {
        return response()->json([
          'IsSuccess' => true,
          'Message' => 'Verify sid successfully.',
          'TotalCount' => 1,
          'Data' => ['inventory_id' => $Inventory->id]
        ], 200);
      } else {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Incorrect sid.',
          'TotalCount' => 0,
          'Data' => null
        ], 200);
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Incorrect sid.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function updateOptions(Request $request)
  {
    $user_id = $request->user_id;
    $account_id = $request->account_id;
    $s_id = $request->s_id;
    $app_option_id = $request->app_option_id;
    $User = User::find($user_id);
    $RoleName = Role::find($User->role);

    $User = User::find($user_id);
    $AppOptionFind = AppOption::find($app_option_id);
    if ($User && $AppOptionFind) {
      UserAppOption::insert([
        'user_id' => $user_id,
        'inventory_id' => $s_id,
        'account_id' => $account_id,
        'app_option_id' => $app_option_id,
        'created_by' => $User->username,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
      ]);

      if (isset($account_id) && $account_id != '') {
        OrderStatus::insert([
          'account_id' => $account_id,
          'role_name' => 'shipping',
          'team' => 'shipping',
          'orderstatus' => $AppOptionFind->name,
          'created_by' => $User->username,
          'created_at' => date("Y-m-d H:i:s"),
          'updated_at' => date("Y-m-d H:i:s")
        ]);
      }

      $AppOptionUpdatedRecord = UserAppOption::latest()->first();

      if ($AppOptionUpdatedRecord) {
        return response()->json([
          'IsSuccess' => true,
          'Message' => 'Successfully update options.',
          'TotalCount' => 1,
          'Data' => $AppOptionUpdatedRecord
        ], 200);
      } else {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Something went wrong.',
          'TotalCount' => 0,
          'Data' => null
        ], 200);
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function lastScanHistory(Request $request)
  {
    $user_id = $request->user_id;
    $User = User::find($user_id);
    $AppOptionFind = UserAppOption::where('user_id', $user_id)->orderBy('id', 'desc')->first();
    $time = date('H', strtotime($AppOptionFind->created_at));
    $ot = false;

    if ($time >= 17)
      $ot = true;
    if ($User && $AppOptionFind) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully get records.',
        'TotalCount' => 1,
        'OT' => $ot,
        'Data' => $AppOptionFind
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function todayTotalScanEntry(Request $request)
  {
    $user_id = $request->user_id;
    $User = User::find($user_id);
    $today = date('Y-m-d');
    $AppOptionFind = UserAppOption::where('user_id', $user_id)->whereDate('created_at', $today)->count();
    if ($User) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully get records.',
        'TotalCount' => $AppOptionFind,
        'Data' => $AppOptionFind
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong. User details not found.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function listOptions()
  {
    $data = [];
    $userId = auth()->user()->id;
    $UserAppOption = UserAppOption::with('user', 'AppOption')->where('user_id', $userId)->get();
    foreach ($UserAppOption as $opt) {
      $key =  array('user' => $opt->user->username, 'sid' => $opt->inventory_id, 'status' => $opt->AppOption->name, 'hid' => $opt->account_id);
      array_push($data, $key);
    }
    return $data;
  }

  public function appVersion()
  {
    $Appversion = Appversion::get();
    $android = '';
    $ios = '';
    foreach ($Appversion as $Appversions) {
      if ($Appversions->name == 'android') {
        $android = $Appversions->version;
      }
      if ($Appversions->name == 'ios') {
        $ios = $Appversions->version;
      }
    }
    $data = array('android' => $android, 'ios' => $ios);

    return response()->json([
      'IsSuccess' => true,
      'Message' => 'List app latest version.',
      'TotalCount' => 2,
      'Data' => $data
    ], 200);
  }

  public function shippingList(Request $request)
  {
    $start = $request->startDate;
    $end = $request->fromDate;

    if ($request->startDate == "" || $request->fromDate == "") {
      $shippingInfoCount = UserAppOption::select('users.id as UserId', 'users.username', DB::raw('SUM(app_options.points) AS TotalPoints'))
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereDate('user_app_options.created_at', Carbon::today())
        ->where('users.role', 4)
        ->groupBy('users.id')
        ->count();

      $shippingInfo = UserAppOption::select('users.id as UserId', 'user_app_options.account_id as account_id', 'users.username', DB::raw('SUM(app_options.points) AS TotalPoints'), DB::raw('COUNT(app_options.id) as TotalParts'))
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereDate('user_app_options.created_at', Carbon::today())
        ->where('users.role', 4)
        ->groupBy('users.id')
        ->get();
    } else {
      $shippingInfoCount = UserAppOption::select('users.id as UserId', 'users.username', DB::raw('SUM(app_options.points) AS TotalPoints'))
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereBetween(DB::raw('date(user_app_options.created_at)'), array($start, $end))
        ->where('users.role', 4)
        ->groupBy('users.id')
        ->count();

      $shippingInfo = UserAppOption::select('users.id as UserId', 'users.username', 'user_app_options.account_id as account_id', DB::raw('SUM(app_options.points) AS TotalPoints'), DB::raw('COUNT(app_options.id) as TotalParts'))
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereBetween(DB::raw('date(user_app_options.created_at)'), array($start, $end))
        ->where('users.role', 4)
        ->groupBy('users.id')
        ->get();
    }

    if ($shippingInfo) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Shipping List.',
        'TotalCount' => $shippingInfoCount,
        'Data' => $shippingInfo
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function shippingByUser(Request $request)
  {
    $start = $request->startDate;
    $end = $request->fromDate;

    if ($request->startDate == "" || $request->fromDate == "") {
      $shippingInfoCount = UserAppOption::select('users.id as UserId', 'app_options.name as task', 'app_options.points', 'user_app_options.created_at')
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereDate('user_app_options.created_at', Carbon::today())
        ->where('user_app_options.user_id', $request->id)
        // ->groupBy('app_options.id')
        ->count();

      $shippingInfo = UserAppOption::select('users.id as UserId', 'app_options.name as task', 'app_options.points', 'user_app_options.created_at', 'user_app_options.account_id as account_id')
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereDate('user_app_options.created_at', Carbon::today())
        ->where('user_app_options.user_id', $request->id)
        // ->groupBy('users.id')
        // ->offset($request->page)
        // ->limit($this->perpage)
        ->get();
    } else {
      $shippingInfoCount = UserAppOption::select('users.id as UserId', 'app_options.name as task', 'app_options.points', 'user_app_options.created_at')
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereBetween(DB::raw('date(user_app_options.created_at)'), array($start, $end))
        ->where('user_app_options.user_id', $request->id)
        // ->groupBy('app_options.id')
        ->count();

      $shippingInfo = UserAppOption::select('users.id as UserId', 'app_options.name as task', 'app_options.points', 'user_app_options.created_at', 'user_app_options.account_id as account_id')
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereBetween(DB::raw('date(user_app_options.created_at)'), array($start, $end))
        ->where('user_app_options.user_id', $request->id)
        // ->offset($request->page)
        // ->limit($this->perpage)
        // ->groupBy('app_options.id')
        ->get();
    }
    $UserName = User::where('id', $request->id)->first();

    if ($shippingInfo) {
      $data = [];
      foreach ($shippingInfo as $shipping) {
        $time = date('H', strtotime($shipping->created_at));
        $ot = false;
        if ($time >= 17)
          $ot = true;
        $shipping['OT'] = $ot;
        array_push($data, $shipping);
      }
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Shipping By User List.',
        'TotalCount' => $shippingInfoCount,
        'UserName' => $UserName->username,
        'Data' => $data
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function programmerList(Request $request)
  {
    $start = $request->startDate;
    $end = $request->fromDate;

    if ($request->startDate == "" || $request->fromDate == "") {
      $shippingInfoCount = UserAppOption::select('users.id as UserId', 'users.username', DB::raw('SUM(app_options.points) AS TotalPoints'))
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereDate('user_app_options.created_at', Carbon::today())
        ->where('users.role', 3)
        ->groupBy('users.id')
        ->count();

      $shippingInfo = UserAppOption::select('users.id as UserId', 'user_app_options.account_id as account_id', 'users.username', DB::raw('SUM(app_options.points) AS TotalPoints'), DB::raw('COUNT(app_options.id) as TotalParts'))
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereDate('user_app_options.created_at', Carbon::today())
        ->where('users.role', 3)
        ->groupBy('users.id')
        ->get();
    } else {
      $shippingInfoCount = UserAppOption::select('users.id as UserId', 'users.username', DB::raw('SUM(app_options.points) AS TotalPoints'))
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereBetween(DB::raw('date(user_app_options.created_at)'), array($start, $end))
        ->where('users.role', 3)
        ->groupBy('users.id')
        ->count();

      $shippingInfo = UserAppOption::select('users.id as UserId', 'users.username', 'user_app_options.account_id as account_id', DB::raw('SUM(app_options.points) AS TotalPoints'), DB::raw('COUNT(app_options.id) as TotalParts'))
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereBetween(DB::raw('date(user_app_options.created_at)'), array($start, $end))
        ->where('users.role', 3)
        ->groupBy('users.id')
        ->get();
    }

    if ($shippingInfo) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Shipping List.',
        'TotalCount' => $shippingInfoCount,
        'Data' => $shippingInfo
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function programmerByUser(Request $request)
  {
    $start = $request->startDate;
    $end = $request->fromDate;

    if ($request->startDate == "" || $request->fromDate == "") {
      $shippingInfoCount = UserAppOption::select('users.id as UserId', 'app_options.name as task', 'app_options.points', 'user_app_options.created_at')
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereDate('user_app_options.created_at', Carbon::today())
        ->where('user_app_options.user_id', $request->id)
        // ->groupBy('app_options.id')
        ->count();

      $shippingInfo = UserAppOption::select('users.id as UserId', 'app_options.name as task', 'app_options.points', 'user_app_options.created_at', 'user_app_options.account_id as account_id')
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereDate('user_app_options.created_at', Carbon::today())
        ->where('user_app_options.user_id', $request->id)
        // ->groupBy('users.id')
        // ->offset($request->page)
        // ->limit($this->perpage)
        ->get();
    } else {
      $shippingInfoCount = UserAppOption::select('users.id as UserId', 'app_options.name as task', 'app_options.points', 'user_app_options.created_at')
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereBetween(DB::raw('date(user_app_options.created_at)'), array($start, $end))
        ->where('user_app_options.user_id', $request->id)
        // ->groupBy('app_options.id')
        ->count();

      $shippingInfo = UserAppOption::select('users.id as UserId', 'app_options.name as task', 'app_options.points', 'user_app_options.created_at', 'user_app_options.account_id as account_id')
        ->leftJoin('users', 'users.id', '=', 'user_app_options.user_id')
        ->leftJoin('app_options', 'app_options.id', '=', 'user_app_options.app_option_id')
        ->whereBetween(DB::raw('date(user_app_options.created_at)'), array($start, $end))
        ->where('user_app_options.user_id', $request->id)
        // ->offset($request->page)
        // ->limit($this->perpage)
        // ->groupBy('app_options.id')
        ->get();
    }
    $UserName = User::where('id', $request->id)->first();

    if ($shippingInfo) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Shipping By User List.',
        'TotalCount' => $shippingInfoCount,
        'UserName' => $UserName->username,
        'Data' => $shippingInfo
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function CreateEasyPost(Request $request)
  {
    $id = $request["fsid"]; // Account id
    $packagetype = $request["packagetype"];

    if ($request["packagetype"]  ==  "") {
      $packagetype = null;
    }
    $pricepaid = $request['pricepaid'];
    //echo $price;
    if ($pricepaid >= 250) {
      $signaturestatus = 'SIGNATURE';
    } elseif ($pricepaid < 250) {
      $signaturestatus = 'NO_SIGNATURE';
    }
    $shiptoname = $request['shiptoname'];
    $customerstreet1 = $request['customerstreet1'];
    $customerstreet2 = $request['customerstreet2'];
    $customercity = $request['customercity'];
    $customerstate = $request['customerstateprovince'];
    $customerzip = $request['customerzippostalcode'];

    if ($request['direction'] == 'To Customer') {
      $toname = $shiptoname;
      $tostreet1 = $customerstreet1;
      $tostreet2 = $customerstreet2;
      $tocity = $customercity;
      $tostate = $customerstate;
      $tozip = $customerzip;

      $fromname =    "Flagship One Inc";
      $fromstreet1 = "19 Wilbur Street";
      $fromstreet2 = "";
      $fromcity =    "Lynbrook";
      $fromstate = "NY";
      $fromzip = "11563";
      $fsid = 'H#' . $id;
    } else {
      $toname = "Flagship One Inc";
      $tostreet1 = "19 wilbur street";
      $tostreet2 = "";
      $tocity = "lynbrook";
      $tostate = "ny";
      $tozip = "11516";

      $fromname =    $shiptoname;
      $fromstreet1 = $customerstreet1;
      $fromstreet2 = $customerstreet2;
      $fromcity =    $customercity;
      $fromstate = $customerstate;
      $fromzip = $customerzip;
      $fsid = 'H#' . $id;
    }

    \EasyPost\EasyPost::setApiKey(getenv('EASYPOST_API_KEY'));

    // create addresses
    $to_address_params = array(
      "name"    => "$toname",
      "street1" => "$tostreet1",
      "street2" => "$tostreet2",
      "city"    => "$tocity",
      "state"   => "$tostate",
      "zip"     => "$tozip",
      "phone"   => "516-766-2223"
    );
    $to_address = \EasyPost\Address::create($to_address_params);

    $from_address_params = array(
      "name"    => "$fromname",
      "street1" => "$fromstreet1",
      "street2" => "$fromstreet2",
      "city"    => "$fromcity",
      "state"   => "$fromstate",
      "zip"     => "$fromzip",
      "phone"   => "516-766-2223"
    );
    $from_address = \EasyPost\Address::create($from_address_params);

    // create parcel
    $parcel_params = array(
      "length"             => 9,
      "width"              => 9,
      "height"             => 9,
      "predefined_package" => $packagetype,
      "weight"             => 48
    );
    $parcel = \EasyPost\Parcel::create($parcel_params);

    $us_state_abbrevs = array('AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA', 'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY', 'AE', 'AA', 'AP');
    if (!in_array($customerstate, $us_state_abbrevs)) {
      $customs_item = \EasyPost\CustomsItem::create(array(
        "description" => 'Car Computer',
        "quantity" => 1,
        "weight" => 48,
        "value" => $pricepaid,
        "hs_tariff_number" => '4011.10',
        "origin_country" => 'US'
      ));
      $customs_info = \EasyPost\CustomsInfo::create(array(
        "customs_items" => array($customs_item)
      ));
      // create shipment
      $shipment_params = array(
        "from_address" => $from_address,
        "to_address"   => $to_address,
        "parcel"       => $parcel,
        "customs_info" => $customs_info,
        "options" => array(
          "print_custom_1" => $fsid,
          "delivery_confirmation" => $signaturestatus
        )
      );
    } else {
      // create shipment
      $shipment_params = array(
        "from_address" => $from_address,
        "to_address"   => $to_address,
        "parcel"       => $parcel,
        "options" => array(
          "print_custom_1" => $fsid,
          "delivery_confirmation" => $signaturestatus
        )
      );
    }
    $shipment = \EasyPost\Shipment::create($shipment_params);

    $options = '<option value="">REQUIRED</option>';
    foreach ($shipment->rates as $rate) {
      if ($packagetype != null && $rate->carrier = 'USPS') {
        $options .= '<option value="' . $rate->carrier . '&' . $rate->service . '&' . $rate->delivery_days . '">' . $rate->carrier . ' - ' . $rate->service . ' - ' . $rate->rate . ' - DelivDays:' . $rate->delivery_date . '' . $rate->delivery_days . '</option>';
      } else {
        if ($rate->carrier = 'UPS') {
          $options .= '<option value="' . $rate->carrier . '&' . $rate->service . '&' . $rate->delivery_days . '">' . $rate->carrier . ' - ' . $rate->service . ' - ' . $rate->rate . ' - DelivDays:' . $rate->delivery_date . '' . $rate->delivery_days . '</option>';
        }
      }
    }
    return response()->json([
      'success' => true,
      'msg' => 'Successfully get data.',
      'totalCount' => 1,
      'data' => array('shipment' => json_decode($shipment), 'options' => $options)
    ], 200);
  }

  public function BuyEasyPost(Request $request)
  {
    // return $request->all(); die;
    $service = $request["RateCarrier"];
    $ServiceData = explode('&', $service);
    $id = $request["fsid"]; // Account id
    $TrackingId = $request["tracking_id"]; // tracking id
    $packagetype = $request["packagetype"];
    if ($request["packagetype"]  ==  "") {
      $packagetype = null;
    }
    $pricepaid = $request['pricepaid'];
    //echo $price;
    if ($pricepaid >= 250) {
      $signaturestatus = 'SIGNATURE';
    } elseif ($pricepaid < 250) {
      $signaturestatus = 'NO_SIGNATURE';
    }
    if ($request['shopname'] != '') {
      $shiptoname = $request['shopname'];
    } else {
      $shiptoname = $request['shiptoname'];
    }
    $customerstreet1 = $request['customerstreet1'];
    $customerstreet2 = $request['customerstreet2'];
    $customercity = $request['customercity'];
    $customerstate = $request['customerstateprovince'];
    $customerzip = $request['customerzippostalcode'];

    if ($request['direction'] == 'To Customer') {
      $toname = $shiptoname;
      $tostreet1 = $customerstreet1;
      $tostreet2 = $customerstreet2;
      $tocity = $customercity;
      $tostate = $customerstate;
      $tozip = $customerzip;

      $fromname =    "Flagship One Inc";
      $fromstreet1 = "19 Wilbur Street";
      $fromstreet2 = "";
      $fromcity =    "Lynbrook";
      $fromstate = "NY";
      $fromzip = "11563";
      $fsid = 'H#' . $id;
    } else {
      $toname = "Flagship One Inc";
      $tostreet1 = "19 wilbur street";
      $tostreet2 = "";
      $tocity = "lynbrook";
      $tostate = "ny";
      $tozip = "11516";

      $fromname =    $shiptoname;
      $fromstreet1 = $customerstreet1;
      $fromstreet2 = $customerstreet2;
      $fromcity =    $customercity;
      $fromstate = $customerstate;
      $fromzip = $customerzip;
      $fsid = 'H#' . $id;
    }

    \EasyPost\EasyPost::setApiKey(getenv('EASYPOST_API_KEY'));

    // create addresses
    $to_address_params = array(
      "name"    => "$toname",
      "street1" => "$tostreet1",
      "street2" => "$tostreet2",
      "city"    => "$tocity",
      "state"   => "$tostate",
      "zip"     => "$tozip",
      "phone"   => "516-766-2223"
    );
    $to_address = \EasyPost\Address::create($to_address_params);

    $from_address_params = array(
      "name"    => "$fromname",
      "street1" => "$fromstreet1",
      "street2" => "$fromstreet2",
      "city"    => "$fromcity",
      "state"   => "$fromstate",
      "zip"     => "$fromzip",
      "phone"   => "516-766-2223"
    );
    $from_address = \EasyPost\Address::create($from_address_params);

    // create parcel
    $parcel_params = array(
      "length"             => 9,
      "width"              => 9,
      "height"             => 9,
      "predefined_package" => $packagetype,
      "weight"             => 48
    );
    $parcel = \EasyPost\Parcel::create($parcel_params);

    $us_state_abbrevs = array('AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA', 'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY', 'AE', 'AA', 'AP');
    if (!in_array($customerstate, $us_state_abbrevs)) {
      $customs_item = \EasyPost\CustomsItem::create(array(
        "description" => 'Car Computer',
        "quantity" => 1,
        "weight" => 48,
        "value" => $pricepaid,
        "hs_tariff_number" => '4011.10',
        "origin_country" => 'US'
      ));
      $customs_info = \EasyPost\CustomsInfo::create(array(
        "customs_items" => array($customs_item)
      ));
      // create shipment
      $shipment_params = array(
        "from_address" => $from_address,
        "to_address"   => $to_address,
        "parcel"       => $parcel,
        "customs_info" => $customs_info,
        "options" => array(
          "print_custom_1" => 'Account#:' . $id,
          "print_custom_2" => 'DaysToDelivery:' . $ServiceData[2],
          "delivery_confirmation" => $signaturestatus
        )
      );
    } else {
      // create shipment
      $shipment_params = array(
        "from_address" => $from_address,
        "to_address"   => $to_address,
        "parcel"       => $parcel,
        "options" => array(
          "print_custom_1" => 'Account#:' . $id,
          "print_custom_2" => 'DaysToDelivery:' . $ServiceData[2],
          "delivery_confirmation" => $signaturestatus
        )
      );
    }
    $shipment = \EasyPost\Shipment::create($shipment_params);

    try {
      foreach ($shipment->rates as $rate) {
        if ($rate->carrier == $ServiceData[0] && $rate->service == $ServiceData[1]) {
          $shipment->buy($rate);
          break;
        }
      }
    } catch (\EasyPost\Error $e) {
      return response()->json([
        'success' => false,
        'msg' => $e->jsonBody['error']['message'],
        'totalCount' => 0,
        'data' => []
      ], 200);
    }

    if ($shipment && isset($shipment->postage_label) && isset($shipment->selected_rate) && isset($shipment->tracker)) {
      $dateNow = date('Y-m-d H:i:s');
      $tracking_status = TrackingStatus::find($TrackingId);
      // $tracking_status->account_id = $id;
      if ($tracking_status->direction == 'InBound') {
        $tracking_status->returntrackinginfo = $shipment->tracker->public_url;
      }
      $tracking_status->shippinglabel = $shipment->postage_label->label_url;
      $tracking_status->shippingrate = $shipment->selected_rate->rate;
      $tracking_status->shippingpackage = $packagetype;
      $tracking_status->deliverydatetocustomer = $shipment->selected_rate->delivery_date;
      $tracking_status->customertrackingnumber = $shipment->tracker->public_url;
      $tracking_status->realtimetracking = $shipment->tracker->public_url;
      $tracking_status->pricefspaidforshipping = $shipment->selected_rate->rate;
      $tracking_status->vendordatepurchased = $shipment->created_at;
      $tracking_status->trackingcode = $shipment->tracking_code;
      $tracking_status->label_creation_date = $dateNow;
      $tracking_status->save();

      return response()->json([
        'success' => true,
        'msg' => 'Successfully get data.',
        'totalCount' => 1,
        'data' => array(
          'shipment' => json_decode($shipment),
          'tracking' => new TrackingStatusResource($tracking_status)
        ),
      ], 200);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Something went wrong.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    }
  }

  public function CreateJunkyardToUs(Request $request)
  {
    $packagetype = $request->input('packagetype');
    $pricepaid = floatval($request->input('pricepaid'));
    $signaturestatus = $pricepaid >= 250 ? 'SIGNATURE' : 'NO_SIGNATURE';

    $fromname = $request->input('suppliershippingname');
    $fromstreet1 = $request->input('suppliershippingstreet');
    $fromstreet2 = "";
    $fromcity = $request->input('suppliershippingcity');
    $fromstate = $request->input('suppliershippingstate');
    $fromzip = $request->input('suppliershippingzip');
    $fsid = 'H#' . $request->input('fsid');

    $toname = "Flagship One Inc";
    $tostreet1 = "19 wilbur street";
    $tostreet2 = "";
    $tocity = "lynbrook";
    $tostate = "ny";
    $tozip = "11516";

    \EasyPost\EasyPost::setApiKey(getenv('EASYPOST_API_KEY'));

    // create addresses
    $toAddress = \EasyPost\Address::create([
      "name"    => $toname,
      "street1" => $tostreet1,
      "street2" => $tostreet2,
      "city"    => $tocity,
      "state"   => $tostate,
      "zip"     => $tozip,
      "phone"   => "516-766-2223",
    ]);

    $fromAddress = \EasyPost\Address::create([
      "name"    => $fromname,
      "street1" => $fromstreet1,
      "street2" => $fromstreet2,
      "city"    => $fromcity,
      "state"   => $fromstate,
      "zip"     => $fromzip,
      "phone"   => "516-766-2223",
    ]);

    // create parcel
    $parcel = \EasyPost\Parcel::create([
      "length"             => 9,
      "width"              => 9,
      "height"             => 9,
      "predefined_package" => $packagetype,
      "weight"             => 48,
    ]);

    $us_state_abbrevs = array('AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA', 'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY', 'AE', 'AA', 'AP');
    if (!in_array($fromstate, $us_state_abbrevs)) {
      $customs_item = \EasyPost\CustomsItem::create(array(
        "description" => 'Car Computer',
        "quantity" => 1,
        "weight" => 48,
        "value" => $pricepaid,
        "hs_tariff_number" => '4011.10',
        "origin_country" => 'US'
      ));
      $customs_info = \EasyPost\CustomsInfo::create(array(
        "customs_items" => array($customs_item)
      ));
      // create shipment
      $shipment = \EasyPost\Shipment::create([
        "from_address" => $fromAddress,
        "to_address"   => $toAddress,
        "parcel"       => $parcel,
        "customs_info" => $customs_info,
        "options"      => [
          "print_custom_1" => $fsid,
          "delivery_confirmation" => $signaturestatus,
        ]
      ]);
    } else {
      // create shipment
      $shipment = \EasyPost\Shipment::create([
        "from_address" => $fromAddress,
        "to_address"   => $toAddress,
        "parcel"       => $parcel,
        "options"      => [
          "print_custom_1" => $fsid,
          "delivery_confirmation" => $signaturestatus,
        ],
      ]);
    }

    $options = '<option value="">REQUIRED</option>';
    foreach ($shipment->rates as $rate) {
      if ($packagetype != null && $rate->carrier = 'USPS') {
        $options .= '<option value="' . $rate->carrier . '&' . $rate->service . '&' . $rate->delivery_days . '">' . $rate->carrier . ' - ' . $rate->service . ' - ' . $rate->rate . ' - DelivDays:' . $rate->delivery_date . '' . $rate->delivery_days . '</option>';
      } else {
        if ($rate->carrier = 'UPS') {
          $options .= '<option value="' . $rate->carrier . '&' . $rate->service . '&' . $rate->delivery_days . '">' . $rate->carrier . ' - ' . $rate->service . ' - ' . $rate->rate . ' - DelivDays:' . $rate->delivery_date . '' . $rate->delivery_days . '</option>';
        }
      }
    }

    return response()->json([
      'success' => true,
      'msg' => 'Successfully get data.',
      'totalCount' => 1,
      'data' => ['shipment' => json_decode($shipment), 'options' => $options],
    ], 200);
  }

  public function BuyEasyPostJunkyard(Request $request)
  {
    $service = $request->input('rate');
    $serviceData = explode('&', $service);
    $trackingId = $request->input('trackingid'); // tracking id
    $packagetype = $request->input('packagetype');
    $pricepaid = floatval($request->input('pricepaid'));
    $signaturestatus = $pricepaid >= 250 ? 'SIGNATURE' : 'NO_SIGNATURE';

    $toname = "Flagship One Inc";
    $tostreet1 = "19 wilbur street";
    $tostreet2 = "";
    $tocity = "lynbrook";
    $tostate = "ny";
    $tozip = "11516";

    $fromname = $request->input('shiptoname');
    $fromstreet1 = $request->input('customerstreet1');
    $fromstreet2 = "";
    $fromcity = $request->input('customercity');
    $fromstate = $request->input('customerstateprovince');
    $fromzip = $request->input('customerzippostalcode');
    $fsid = 'H#' . $request->input('fsid');

    \EasyPost\EasyPost::setApiKey(getenv('EASYPOST_API_KEY'));

    // create addresses
    try {
      $toAddress = \EasyPost\Address::create([
        "name"    => $toname,
        "street1" => $tostreet1,
        "street2" => $tostreet2,
        "city"    => $tocity,
        "state"   => $tostate,
        "zip"     => $tozip,
        "phone"   => "516-766-2223",
      ]);
    } catch (\EasyPost\Error $e) {
      return response()->json([
        'success' => false,
        'msg' => $e,
        // 'msg' => 'Try again in a few seconds.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    }

    try {
      $fromAddress = \EasyPost\Address::create([
        "name"    => $fromname,
        "street1" => $fromstreet1,
        "street2" => $fromstreet2,
        "city"    => $fromcity,
        "state"   => $fromstate,
        "zip"     => $fromzip,
      ]);
    } catch (\EasyPost\Error $e) {
      return response()->json([
        'success' => false,
        'msg' => $e,
        // 'msg' => 'Try again in a few seconds.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    }

    // create parcel
    try {
      $parcel = \EasyPost\Parcel::create([
        "length"             => 9,
        "width"              => 9,
        "height"             => 9,
        "predefined_package" => $packagetype,
        "weight"             => 48
      ]);
    } catch (\EasyPost\Error $e) {
      return response()->json([
        'success' => false,
        'msg' => $e,
        // 'msg' => 'Try again in a few seconds.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    }

    $us_state_abbrevs = array('AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA', 'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY', 'AE', 'AA', 'AP');
    if (!in_array($fromstate, $us_state_abbrevs)) {
      $customs_item = \EasyPost\CustomsItem::create(array(
        "description" => 'Car Computer',
        "quantity" => 1,
        "weight" => 48,
        "value" => $pricepaid,
        "hs_tariff_number" => '4011.10',
        "origin_country" => 'US'
      ));
      $customs_info = \EasyPost\CustomsInfo::create(array(
        "customs_items" => array($customs_item)
      ));

      // create shipment
      try {
        $shipment = \EasyPost\Shipment::create([
          "from_address" => $fromAddress,
          "to_address"   => $toAddress,
          "parcel"       => $parcel,
          'customs_info' => $customs_info,
          "options"      => [
            "print_custom_1" => $fsid,
            "print_custom_2" => 'DaysToDelivery:' . $serviceData[2],
            "delivery_confirmation" => $signaturestatus
          ],
        ]);
      } catch (\EasyPost\Error $e) {
        return response()->json([
          'success' => false,
          'msg' => $e,
          // 'msg' => 'Try again in a few seconds.',
          'totalCount' => 0,
          'data' => []
        ], 200);
      }
    } else {
      // create shipment
      try {
        $shipment = \EasyPost\Shipment::create([
          "from_address" => $fromAddress,
          "to_address"   => $toAddress,
          "parcel"       => $parcel,
          "options"      => [
            "print_custom_1" => $fsid,
            "print_custom_2" => 'DaysToDelivery:' . $serviceData[2],
            "delivery_confirmation" => $signaturestatus
          ]
        ]);
      } catch (\EasyPost\Error $e) {
        return response()->json([
          'success' => false,
          'msg' => $e,
          // 'msg' => 'Try again in a few seconds.',
          'totalCount' => 0,
          'data' => []
        ], 200);
      }
    }

    try {
      foreach ($shipment->rates as $rate) {
        if ($rate->carrier == $serviceData[0] && $rate->service == $serviceData[1]) {
          $shipment->buy($rate);
          break;
        }
      }
    } catch (\EasyPost\Error $e) {
      return response()->json([
        'success' => false,
        'msg' => $e->jsonBody['error']['message'],
        // 'msg' => 'Try again in a few seconds.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    }

    if ($shipment && isset($shipment->selected_rate) && isset($shipment->tracker)) {
      $dateNow = date('Y-m-d H:i:s');
      $tracking_status = Vendor::find($trackingId);
      $tracking_status->rate = $shipment->selected_rate->rate;
      $tracking_status->packagetype = $packagetype;
      $tracking_status->deliverydate = $shipment->selected_rate->delivery_date;
      $tracking_status->trackingnumber = $shipment->tracker->public_url;
      $tracking_status->shippinglabel = $shipment->postage_label->label_url;
      $tracking_status->pricepaid = $shipment->selected_rate->rate;
      $tracking_status->shippingprice = $shipment->selected_rate->rate;
      $tracking_status->datepurchased = date('Y-m-d', strtotime($shipment->created_at));
      $tracking_status->trackingcode = $shipment->tracking_code;
      $tracking_status->label_creation_date = $dateNow;
      $tracking_status->save();

      return response()->json([
        'success' => true,
        'msg' => 'Successfully get data.',
        'totalCount' => 1,
        'data' => [
          'shipment' => json_decode($shipment),
          'tracking' => new VendorResource($tracking_status),
        ]
      ], 200);
    } else {
      return response()->json([
        'success' => false,
        'msg' => 'Something went wrong.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    }
  }

  public function testCreate()
  {
    $address_params = array(
      "street1" => "388 Townsend St",
      "street2" => "Apt 20",
      "city"    => "San Francisco",
      "state"   => "CA",
      "zip"     => "94107"
    );
    $address = \EasyPost\Address::create($address_params);
    return $address;
  }

  public function testRetrieve(Request $request)
  {
    try {
      $retrieved_address = \EasyPost\Address::retrieve($request->id);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
    // $this->assertInstanceOf('\EasyPost\Address', $retrieved_address);
    // $this->assertEquals($retrieved_address->id, $address->id);
    // $this->assertEquals($retrieved_address, $address);
    if ($retrieved_address) {
      return $retrieved_address;
    } else {
      return 'Not found';
    }
  }

  public function updateInventory(Request $request)
  {
    if (isset($request->inventory_id) && $request->inventory_id != '') {
      $Inventory = Inventory::find($request->inventory_id);
      $Inventory->xyzposition = $request->xyzposition;
      $Inventory->save();

      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully update inventory xyzposition.',
        'TotalCount' => 1,
        'Data' => array('xyzposition' => $Inventory->xyzposition)
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function updateCore(Request $request)
  {
    if (isset($request->core_id) && $request->core_id != '') {
      $Core = Core::find($request->core_id);
      $Core->xyzposition = $request->xyzposition;
      $Core->save();

      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully update core xyz position.',
        'TotalCount' => 1,
        'Data' => array('xyzposition' => $Core->xyzposition)
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function checkpcmhw(Request $request)
  {
    $checkpcmhw = $request->pcmhw;
    $Pcmhwtesting = Pcmhwtesting::where('pcmhw', $checkpcmhw)->first();
    if ($Pcmhwtesting) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully get data.',
        'TotalCount' => 1,
        'Data' => $Pcmhwtesting
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Not found record.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function ChangeOnBoardTesting(Request $request)
  {
    $account_id = $request->account_id;
    $on_board_testing = $request->on_board_testing;
    $account = Account::find($account_id);
    if ($account) {
      $account->onboard_testing = $on_board_testing;
      $account->save();

      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully updated records.',
        'TotalCount' => 0,
        'Data' => array('on_board_testing' => $on_board_testing)
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Not found account details.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function savepcmhw(Request $request)
  {
    if (isset($request->pcmhw) && $request->pcmhw != '') {
      $Pcmhwtesting = Pcmhwtesting::create($request->all());
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully added record.',
        'TotalCount' => 1,
        'Data' => $Pcmhwtesting
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Please enter details.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function easypostwebhook(Request $request)
  {
    if ($request["easypostpassword"] == "CharlesParkEasyPostMe!") {
      $result = file_get_contents('php://input');
      \EasyPost\EasyPost::setApiKey(getenv('EASYPOST_API_KEY'));
      $result = \EasyPost\Event::receive($result);
      if ($result) {
        $carrier = $result->result->carrier;
        $trackingcode = $result->result->tracking_code;
        $trackingdetails = $result->result->tracking_details;
        $description = $result->description;

        if ($description == "tracker.created") {
          $pretransit = 'pre_transit';
        } elseif ($description == "refund.successful") {
          $pretransit = 'pre_transit';
        }

        for ($i = 0; $i < count($trackingdetails); $i++) {
          if ($trackingdetails[$i]->status == "pre_transit") {
            $pretransit = 'pre_transit';
          }
        }


        for ($i = 0; $i < count($trackingdetails); $i++) {
          if ($trackingdetails[$i]->status == "in_transit") {
            $intransit = 'in_transit';
          }
        }

        //$sql="insert ignore into refundlabel(id,fsid,carrier) VALUES(NULL,'77','$pretransit','$intransit')";
        // $sql = "update ignore vendors set pretransit='$pretransit',intransit='$intransit' where trackingcode='$trackingcode'";
        $EasypostWebhook = new EasypostWebhook();
        $EasypostWebhook->pretransit = $pretransit;
        $EasypostWebhook->intransit = $intransit;
        $EasypostWebhook->trackingcode = $trackingcode;
        $EasypostWebhook->save();
      }
    }

    if ($request["charles"] == "anotherkeyeigenvector" or $argv[1] == "anotherkeyeigenvector") {
      \EasyPost\EasyPost::setApiKey(getenv('EASYPOST_API_KEY'));

      //current time
      date_default_timezone_set('America/New_York');

      $FiftineDays = date('Y-m-d', strtotime("-15 days"));
      $Vendors = Vendor::with('EasypostWebhook')->whereHas('EasypostWebhook', function ($qr) {
        $qr->where('pretransit', 'pre_transit')->where('intransit', null);
      })->whereDate('label_creation_date', '<', $FiftineDays)->where('refunded', null)->get();

      foreach ($Vendors as $Vendor) {
        $carrier = $Vendor["carrier"];
        $tracking_codes = $Vendor["trackingcode"];

        $refund = \EasyPost\Refund::create(array(
          "carrier" => "$carrier",
          "tracking_codes" => "$tracking_codes"
        ));

        $all = \EasyPost\Refund::all();

        // $sqlrefund = "update ignore vendors set refunded='refunded' where trackingcode='$tracking_codes'";
        $VendorUpdate = Vendor::where('trackingcode', $tracking_codes)->first();
        $VendorUpdate->refunded = 'refunded';
        $VendorUpdate->save();
      }
    }
  }

  public function AutomaticRefund(Request $request)
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

  public function easypostwebhookPage()
  {
    if ($_GET["easypostpassword"] == "CharlesParkEasyPostMe!") {
      $result = file_get_contents('php://input');
      \EasyPost\EasyPost::setApiKey(getenv('EASYPOST_API_KEY'));
      $result = \EasyPost\Event::receive($result);
      if ($result) {
        $carrier = $result->result->carrier;
        $trackingcode = $result->result->tracking_code;
        $trackingdetails = $result->result->tracking_details;
        $description = $result->description;
        $intransit = '';

        if ($description == "tracker.created") {
          $pretransit = 'pre_transit';
        } else if ($description == "refund.successful") {
          $pretransit = 'pre_transit';
        } else {
          $pretransit = '';
        }

        for ($i = 0; $i < count($trackingdetails); $i++) {
          if ($trackingdetails[$i]->status == "pre_transit") {
            $pretransit = 'pre_transit';
          }
        }

        for ($i = 0; $i < count($trackingdetails); $i++) {
          if ($trackingdetails[$i]->status == "in_transit") {
            $intransit = 'in_transit';
          }
        }
        $EasypostWebhook = new EasypostWebhook();
        $EasypostWebhook->pretransit = $pretransit;
        $EasypostWebhook->intransit = $intransit;
        $EasypostWebhook->trackingcode = $trackingcode;
        $EasypostWebhook->save();
      }
    }
  }

  public function GetRmaFormsList(Request $request)
  {
    // return $request->all();
    $account_id = $request->account_id;
    $RmaForms = RmaForms::where('account_id', $account_id)->orderBy('created_at', 'desc')->get();
    if ($RmaForms) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully get data.',
        'TotalCount' => 1,
        'Data' => $RmaForms
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function GetRmaFormsData(Request $request)
  {
    // return $request->all();
    $account_id = $request->account_id;
    $form_id = $request->form_id;
    $Account = Account::with('customermetadata')->where('id', $account_id)->first();
    $RmaForms = RmaForms::where('account_id', $account_id)->where('id', $form_id)->first();
    $return = [];
    if ($Account) {
      $return['account'] = array(
        'account_id' => $Account->id,
        'primaryaccountmanager' => $Account['primaryaccountmanager'],
        'secondaryaccountmanager' => $Account['secondaryaccountmanager'],
        'customervin' => $Account['customervin'],
        'wrongpart' => $Account['wrongpart']
      );
      $return['customermetadata'] = array(
        'shopname' => $Account->customermetadata['shopname'],
        'shipto'  => $Account->customermetadata['shipto'],
        'street1' => $Account->customermetadata['street1'],
        'street2' => $Account->customermetadata['street2'],
        'city' => $Account->customermetadata['city'],
        'state' => $Account->customermetadata['state'],
        'country' => $Account->customermetadata['country'],
        'zip' => $Account->customermetadata['zip'],
      );

      if ($RmaForms) {
        $return['rmaformdata'] = array(
          'id' => $RmaForms->id,
          'account_id' => $RmaForms->account_id,
          'original_problem' => $RmaForms->original_problem,
          'steps_taken_to_diagnose_problem' => $RmaForms->steps_taken_to_diagnose_problem,
          'problems_with_part' => $RmaForms->problems_with_part,
          'steps_taken_to_diagnose_with_part' => $RmaForms->steps_taken_to_diagnose_with_part,
          'additional_notes' => $RmaForms->additional_notes,
          'customer_signature' => $RmaForms->customer_signature,
          'customer_name' => $RmaForms->customer_name,
          'created_at' => (string) $RmaForms->created_at,
        );
        if ($RmaForms->customer_signature != '') {
          $img =  $RmaForms->customer_signature;
          $return['rmaformdata']['FullImage'] = public_path('images/CustomerSignature/') . $img;
          $return['rmaformdata']['CustomerSignature'] = $img;
        } else {
          $return['rmaformdata']['FullImage'] = '';
          $return['rmaformdata']['CustomerSignature'] = '';
        }
      } else {
        $return['rmaformdata'] = [];
      }
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully get data.',
        'TotalCount' => 1,
        'Data' => $return
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'You have already filled out RMA FORM.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function CreateRmaForms(Request $request)
  {
    // return $request->all();
    $file = $request->CustomerSignature;  // image file data

    $FromData = $request->FromData;
    $RmaForms =  RmaForms::where('token', $FromData["token"])->first();
    if ($file != '') {
      $name = "CustomerSignature-" . time() . ".png";
      $path = public_path('images/CustomerSignature/') . $name;
      $img = $file;
      $img = substr($img, strpos($img, ",") + 1);
      $data = base64_decode($img);
      $success = file_put_contents($path, $data);
    } else {
      $name = "";
    }

    if ($RmaForms) {
      if ($file != '' && $RmaForms->customer_signature != '') {
        $image_data = public_path("images/CustomerSignature/{$FromData["customer_signature"]}");
        if (File::exists($image_data)) {
          //unlink($image_data);
        }
      }
      $RmaForms->original_problem = $FromData["original_problem"];
      $RmaForms->steps_taken_to_diagnose_problem = $FromData["steps_taken_to_diagnose_problem"];
      $RmaForms->problems_with_part = $FromData["problems_with_part"];
      $RmaForms->steps_taken_to_diagnose_with_part = $FromData["steps_taken_to_diagnose_with_part"];
      $RmaForms->additional_notes = $FromData["additional_notes"];
      if ($file != '') {
        $RmaForms->customer_signature = $name;
      }
      $RmaForms->token = '';
      $RmaForms->customer_name = $FromData["customer_name"];
      $RmaForms->save();

      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully added data.',
        'TotalCount' => 1,
        'Data' => $RmaForms
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function GetDocusignData(Request $request)
  {
    // return $request->all();
    $account_id = $request->account_id;
    $Account = Account::select('id', 'pricepaid')->with('trackingstatusLast')->where('id', $account_id)->first();
    $return = [];
    if (isset($Account->trackingstatusLast[0])) {
      $return['pricefspaidforshipping'] = $Account->trackingstatusLast[0]['pricefspaidforshipping'];
    } else {
      $return['pricefspaidforshipping'] = 0;
    }
    $return['id'] = $Account['id'];
    $return['pricepaid'] = $Account['pricepaid'];
    $DocusignForm = DocusignForms::where('account_id', $Account->id)->first();
    $return['DocusignForm'] = $DocusignForm;

    if ($Account) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully get data.',
        'TotalCount' => 1,
        'Data' => $return
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function CreateDocusignForm(Request $request)
  {
    // return $token = Str::random(12);
    // return $request->all();
    $file = $request->CustomerSignature;  // image file data
    $FromData = $request->FromData;
    $DocusignForms =  DocusignForms::where('token', $FromData['token'])->first();
    if ($file != '') {
      $name = "CustomerSignature-" . time() . ".png";
      $path = public_path('images/CustomerSignature/') . $name;
      $img = $file;
      $img = substr($img, strpos($img, ",") + 1);
      $data = base64_decode($img);
      $success = file_put_contents($path, $data);
    } else {
      $name = "";
    }
    $token = Str::random(12);
    if ($DocusignForms) {
      if ($file != '') {
        $DocusignForms->customer_signature = $name;
      }
      $DocusignForms->save();
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong. Docusign form not found!',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }

    if ($DocusignForms) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully added data.',
        'TotalCount' => 1,
        'Data' => $DocusignForms
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function GetDocusignDataByToken(Request $request)
  {
    $token = $request->token;
    $DocusignForm = DocusignForms::where('token', $token)->first();
    if ($DocusignForm) {
      if ($DocusignForm->customer_signature != '') {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Docusign completed!',
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      }
      // return $today = date('Y-m-d H:i:s');
      $to = \Carbon\Carbon::now();
      $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $DocusignForm->created_at);
      $diff_in_hours = $to->diffInHours($from);
      if ($diff_in_hours >= 24) {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Token expired!',
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      } else {
        $Account = Account::select('id', 'pricepaid')->with('trackingstatusLast')->where('id', $DocusignForm->account_id)->first();
        $return = [];
        if (isset($Account->trackingstatusLast[0])) {
          $return['pricefspaidforshipping'] = $Account->trackingstatusLast[0]['pricefspaidforshipping'];
        } else {
          $return['pricefspaidforshipping'] = 0;
        }
        $return['id'] = $Account['id'];
        $return['pricepaid'] = $Account['pricepaid'];
        $DocusignForm = DocusignForms::where('account_id', $Account->id)->first();
        $return['DocusignForm'] = $DocusignForm;

        if ($Account) {
          return response()->json([
            'IsSuccess' => true,
            'Message' => 'Successfully get data.',
            'TotalCount' => 1,
            'Data' => $return
          ], 200);
        } else {
          return response()->json([
            'IsSuccess' => false,
            'Message' => 'Something went wrong.',
            'TotalCount' => 0,
            'Data' => []
          ], 200);
        }
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Docusign form not found.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function GetRMADataByToken(Request $request)
  {
    $token = $request->token;
    $RmaForm = RmaForms::where('token', $token)->first();
    if ($RmaForm) {
      if ($RmaForm->customer_signature != '') {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'RMA completed!',
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      }
      $account_id = $RmaForm->account_id;
      $Account = Account::with('customermetadata')->where('id', $account_id)->first();
      $return = [];
      if ($Account) {
        $return['account'] = array(
          'account_id' => $Account->id,
          'primaryaccountmanager' => $Account['primaryaccountmanager'],
          'secondaryaccountmanager' => $Account['secondaryaccountmanager'],
          'customervin' => $Account['customervin'],
          'wrongpart' => $Account['wrongpart']
        );
        $return['customermetadata'] = array(
          'shopname' => $Account->customermetadata['shopname'],
          'shipto'  => $Account->customermetadata['shipto'],
          'street1' => $Account->customermetadata['street1'],
          'street2' => $Account->customermetadata['street2'],
          'city' => $Account->customermetadata['city'],
          'state' => $Account->customermetadata['state'],
          'country' => $Account->customermetadata['country'],
          'zip' => $Account->customermetadata['zip'],
        );

        $return['rmaformdata'] = [];
        return response()->json([
          'IsSuccess' => true,
          'Message' => 'Successfully get data.',
          'TotalCount' => 1,
          'Data' => $return
        ], 200);
      } else {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'You have already filled out RMA FORM.',
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'You have already filled out RMA FORM.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function SendDocusignFormEmail(Request $request)
  {
    // return $token = Str::random(12);
    // return $request->all();
    $DocusignForms =  DocusignForms::where('account_id', $request->account_id)->first();
    $Account =  Account::with('customermetadata')->find($request->account_id);
    $customer_meta_data_id = $Account->customermetadata['id'];
    $email = $Account->customermetadata['email'];
    $shipto = $Account->customermetadata['shipto'];
    if ($email == '') {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Email id not found.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
    $token = Str::random(12);
    if ($DocusignForms) {
      // DocusignForms::where('account_id', $request->account_id)->delete();
      $DocusignForms->account_id = $Account->id;
      $DocusignForms->customer_meta_data_id = $customer_meta_data_id;
      $DocusignForms->token = $token;
      $DocusignForms->customer_signature = null;
      $DocusignForms->created_at = Carbon::now();
      $DocusignForms->save();
    } else {
      $DocusignForms = new DocusignForms();
      $DocusignForms->account_id = $Account->id;
      $DocusignForms->customer_meta_data_id = $customer_meta_data_id;
      $DocusignForms->token = $token;
      $DocusignForms->save();
    }
    if ($DocusignForms) {
      $data = array(
        'url' => getenv('APP_URL') . '/accounts/docusign/create/' . $token,
        'email' => $email,
        'CustomerName' => $shipto
      );
      try {
        Mail::send('emails.docusign', $data, function ($message) use ($email) {
          $message->from(getenv('FROM_EMAIL_ADDRESS'), 'FlagShip One Inc');
          $message->to($email)->subject('Docusign Form');
        });
      } catch (Exception $e) {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Send email fail. ' . $e,
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      }
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully send Email to customer.',
        'TotalCount' => 1,
        'Data' => $DocusignForms
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong!',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function SendRmaFormEmail(Request $request)
  {
    $Account =  Account::with('customermetadata')->find($request->account_id);
    $email = $Account->customermetadata['email'];
    $shipto = $Account->customermetadata['shipto'];
    if ($email == '') {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Email id not found.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
    $token = Str::random(12);
    $RmaForms = new RmaForms();
    $RmaForms->account_id = $Account->id;
    $RmaForms->token = $token;
    $RmaForms->save();
    if ($RmaForms) {
      $data = array(
        'url' => getenv('APP_URL') . '/accounts/rmaform/create/' . $token,
        'email' => $email,
        'CustomerName' => $shipto
      );
      try {
        Mail::send('emails.rma', $data, function ($message) use ($email) {
          $message->from(getenv('FROM_EMAIL_ADDRESS'), 'FlagShip One Inc');
          $message->to($email)->subject('RMA Form');
        });
      } catch (Exception $e) {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Send email fail. ' . $e,
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      }
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully send Email to customer.',
        'TotalCount' => 1,
        'Data' => $RmaForms
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong!',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function docusignCreate()
  {
    return view('common.docusign');
  }

  public function PaypalPoints(Request $request)
  {
    // return $request->all();
    $account_id = $request->account_id;
    $paypal_id = $request->paypal_id;
    $price_paid = $request->price_paid;
    $user_name = auth()->user()->username;
    $Account = Account::find($account_id);
    if ($Account) {
      $AccountPoints = new AccountManagerPoint();
      $AccountPoints->account_id = $account_id;
      $AccountPoints->user_name = $user_name;
      $AccountPoints->price_paid = $price_paid;
      $AccountPoints->paypal_id = $paypal_id;
      $AccountPoints->save();
      if ($AccountPoints) {
        return response()->json([
          'IsSuccess' => true,
          'Message' => 'Successfully added record.',
          'TotalCount' => 1,
          'Data' => $AccountPoints
        ], 200);
      } else {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Something went wrong!',
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong, Account details not found!',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function LateShipmentRemoval(Request $request)
  {
    // return $request->all();
    $account_id = $request->account_id;
    $type = "lateshipmentremoval";
    // $user_name = auth()->user()->username;
    $total = 250;
    $Account = Account::find($account_id);
    if ($Account->pricepaid > $total) {
      $total = $Account->pricepaid;
    }
    if ($Account) {
      $AccountPoints = new AccountManagerPoint();
      $AccountPoints->account_id = $account_id;
      $AccountPoints->user_name = auth()->user()->username;;
      $AccountPoints->type = $type;
      $AccountPoints->total = $total;
      $AccountPoints->save();
      if ($AccountPoints) {
        return response()->json([
          'IsSuccess' => true,
          'Message' => 'Successfully added record.',
          'TotalCount' => 1,
          'Data' => $AccountPoints
        ], 200);
      } else {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Something went wrong!',
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong, Account details not found!',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function CaseClosedPoints(Request $request)
  {
    // return $request->all();
    $account_id = $request->account_id;
    $sr_number = $request->sr_number;
    $price_paid = $request->price_paid;
    $user_name = auth()->user()->username;
    $Account = Account::find($account_id);
    if ($Account) {
      $AccountPoints = new AccountManagerPoint();
      $AccountPoints->account_id = $account_id;
      $AccountPoints->user_name = $user_name;
      $AccountPoints->price_paid = $price_paid;
      $AccountPoints->sr_number = $sr_number;
      $AccountPoints->save();
      if ($AccountPoints) {
        return response()->json([
          'IsSuccess' => true,
          'Message' => 'Successfully added record.',
          'TotalCount' => 1,
          'Data' => $AccountPoints
        ], 200);
      } else {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Something went wrong!',
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong, Account details not found!',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function NegativeFeedbackRemovedPoints(Request $request)
  {
    // return $request->all();
    $account_id = $request->account_id;
    $sr_number = $request->sr_number;
    $user_name = auth()->user()->username;
    $total = 2000;
    $Account = Account::find($account_id);
    if ($Account) {
      $AccountPoints = new AccountManagerPoint();
      $AccountPoints->account_id = $account_id;
      $AccountPoints->user_name = $user_name;
      $AccountPoints->sr_number = $sr_number;
      $AccountPoints->total = $total;
      $AccountPoints->save();
      if ($AccountPoints) {
        return response()->json([
          'IsSuccess' => true,
          'Message' => 'Successfully added record.',
          'TotalCount' => 1,
          'Data' => $AccountPoints
        ], 200);
      } else {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Something went wrong!',
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong, Account details not found!',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function BBBPoints(Request $request)
  {
    // return $request->all();
    $account_id = $request->account_id;
    $user_name = auth()->user()->username;
    $total = 2000;
    $Account = Account::find($account_id);
    if ($Account) {
      $AccountPoints = new AccountManagerPoint();
      $AccountPoints->account_id = $account_id;
      $AccountPoints->user_name = $user_name;
      $AccountPoints->total = $total;
      $AccountPoints->save();
      if ($AccountPoints) {
        return response()->json([
          'IsSuccess' => true,
          'Message' => 'Successfully added record.',
          'TotalCount' => 1,
          'Data' => $AccountPoints
        ], 200);
      } else {
        return response()->json([
          'IsSuccess' => false,
          'Message' => 'Something went wrong!',
          'TotalCount' => 0,
          'Data' => []
        ], 200);
      }
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong, Account details not found!',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function RMAFormCreate()
  {
    return view('common.rmaform');
  }

  public function SecondaryManagerPoint(Request $request)
  {
    $Account = Account::find($request->account_id);
    $last_vendor = $Account
      ->vendor()
      ->select([
        'partprice',
        'shippingprice',
        'pickupcharge',
        'salespersonstockno',
        'datepurchased',
        'in_stock'
      ])
      ->latest('updated_at')
      ->first();
    $account_team = $Account->accountteam;
    $user_name = auth()->user()->username;
    // $user_name = 'testadmin';

    $price_paid = doubleval($Account->pricepaid);
    $part_price = doubleval($last_vendor['partprice']);
    $shipping_price = doubleval($last_vendor['shippingprice']);
    $pickup_charge = doubleval($last_vendor['pickupcharge']);
    $stock_number = $last_vendor['salespersonstockno'];
    $multiplier = intval($Account->contestmultiplier);
    $shipping_date = $Account->trackingstatus()
      ->select('dateshippedtocustomer')
      ->where('direction', 'OutBound')
      ->orderBy('id', 'desc')
      ->first();
    if ($shipping_date) {
      $dateshippedtocustomer = $shipping_date->dateshippedtocustomer;
    } else {
      $dateshippedtocustomer = '';
    }

    // secondary account manager points save/update
    if ($Account->secondaryaccountmanager) {
      AccountManagerPoint::updateOrCreate(
        [
          'account_id' => $Account->id,
          'user_name' => $user_name,
          'type' => 'secondaryaccountmanager',
        ],
        [
          'team' => $account_team,
          'seller' => $Account->secondaryaccountmanager,
          'price_paid' => $price_paid,
          'part_price' => $part_price,
          'shipping_price' => $shipping_price,
          'pickup_charge' => $pickup_charge,
          'stock_number' => $stock_number,
          'multiplier' => $multiplier,
          'shipping_date' => $dateshippedtocustomer,
          'total' => 100,
        ]
      );
      return response()->json([
        'success' => true,
        'message' => 'Successfully added points.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Secondary manager	not found.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    }
  }

  public function SecondaryManagerPointHistory(Request $request)
  {
    $account_id = $request->account_id;
    $AccountManagerPoints = AccountManagerPoint::select('account_id', 'user_name', 'total')->where('type', 'secondaryaccountmanager')->where('account_id', $account_id)->get();
    // secondary account manager points save/update
    if ($AccountManagerPoints) {
      return response()->json([
        'success' => true,
        'message' => 'Successfully added points.',
        'totalCount' => 0,
        'data' => $AccountManagerPoints
      ], 200);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Something went wrong.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    }
  }

  public function PointsBreakdown(Request $request)
  {
    $account_id = $request->account_id;
    $AccountManagerPoints = AccountManagerPoint::select('id', 'account_id', 'user_name', 'total', 'type', 'created_at')->where('account_id', $account_id)->orderBy('type')->get();
    // secondary account manager points save/update
    if ($AccountManagerPoints) {
      return response()->json([
        'success' => true,
        'message' => 'Successfully added points.',
        'totalCount' => 0,
        'data' => $AccountManagerPoints
      ], 200);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Something went wrong.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    }
  }

  public function PointsBreakdownDelete(Request $request)
  {
    $id = $request->id;
    $success = AccountManagerPoint::where('id', $id)->delete();

    $account_id = $request->account_id;
    $AccountManagerPoints = AccountManagerPoint::select('id', 'account_id', 'user_name', 'total', 'type', 'created_at')->where('account_id', $account_id)->orderBy('type')->get();
    // secondary account manager delete
    if ($success) {
      return response()->json([
        'success' => true,
        'message' => 'Successfully deleted points.',
        'totalCount' => 0,
        'data' => $AccountManagerPoints
      ], 200);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Something went wrong.',
        'totalCount' => 0,
        'data' => []
      ], 200);
    }
  }

  public function EbayServices()
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
        'ebayusername' => $ebay_id,
        'salesrecord' => $record_number,
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
      if ($count == 5)
        break;
    }
    return 'Record added: ' . $count;
  }

  public function StockMaster()
  {
    $StockMaster = StockMaster::get();
    return response()->json([
      'success' => true,
      'message' => 'Successfully get stock list.',
      'totalCount' => 0,
      'data' => $StockMaster
    ], 200);
  }

  public function GetApiOrderInsert()
  {
    $ApiOrderinsert = ApiOrderinsert::get();
    return response()->json([
      'success' => true,
      'message' => 'Successfully get api order insert list.',
      'totalCount' => 0,
      'data' => $ApiOrderinsert
    ], 200);
  }

  public function UpdateApiOrderInsert(Request $request)
  {
    // return $request->all(); die;
    $ApiOrderinsert = $request->ApiOrderinsert;
    foreach ($ApiOrderinsert as $ApiOrderinserts) {
      $ApiOrder = ApiOrderinsert::where('team', $ApiOrderinserts['team'])->first();
      if ($ApiOrder) {
        $ApiOrder->valuefrom = $ApiOrderinserts['valuefrom'];
        $ApiOrder->valueto = $ApiOrderinserts['valueto'];
        $ApiOrder->save();
      }
    }
    $ApiOrderinsert = ApiOrderinsert::get();
    return response()->json([
      'success' => true,
      'message' => 'Successfully updated api order insert list.',
      'totalCount' => 0,
      'data' => $ApiOrderinsert
    ], 200);
  }

  public function RmaPdfDownload(Request $request)
  {
    $account_id = $request->account_id;
    $form_id = $request->form_id;
    // $account_id = 24;
    // $form_id = 6;
    $Acc = Account::find($account_id);
    $MetaData = CustomerMetaData::where('account_id', $account_id)->first();
    $RmaForms = RmaForms::where('id', $form_id)->where('account_id', $account_id)->first();
    // $RmaForms = RmaForms::find(2);

    $shipto = '';
    $shopname = '';
    $street1 = '';
    $street2 = '';
    $city = '';
    $state = '';
    $zip = '';
    $original_problem = '';
    $steps_taken_to_diagnose_problem = '';
    $problems_with_part = '';
    $steps_taken_to_diagnose_with_part = '';
    $additional_notes = '';
    $customer_signature = '';
    $customer_name = '';

    if ($MetaData) {
      $shipto = $MetaData->shipto;
      $shopname = $MetaData->shopname;
      $street1 = $MetaData->street1;
      $street2 = $MetaData->street2;
      $city = $MetaData->city;
      $state = $MetaData->state;
      $zip = $MetaData->zip;
    }
    if ($RmaForms) {
      $original_problem = $RmaForms->original_problem;
      $steps_taken_to_diagnose_problem = $RmaForms->steps_taken_to_diagnose_problem;
      $problems_with_part = $RmaForms->problems_with_part;
      $steps_taken_to_diagnose_with_part = $RmaForms->steps_taken_to_diagnose_with_part;
      $additional_notes = $RmaForms->additional_notes;
      $customer_signature = public_path('images/CustomerSignature/') . $RmaForms->customer_signature;
      $customer_name = $RmaForms->customer_name;
    }

    $data = array(
      'shipto' => $shipto,
      'shopname' => $shopname,
      'Account' => $Acc,
      'MetaData' => $MetaData,
      'street1' => $street1,
      'street2' => $street2,
      'city' => $city,
      'state' => $state,
      'zip' => $zip,
      'original_problem' => $original_problem,
      'steps_taken_to_diagnose_problem' => $steps_taken_to_diagnose_problem,
      'problems_with_part' => $problems_with_part,
      'steps_taken_to_diagnose_with_part' => $steps_taken_to_diagnose_with_part,
      'additional_notes' => $additional_notes,
      'customer_signature' => $customer_signature,
      'customer_name' => $customer_name,
    );

    // $data = ['title' => 'Welcome to HDTuto.com'];
    $pdf = PDF::loadView('rmaform', $data);

    $content = $pdf->download()->getOriginalContent();
    // Storage::put('app/public/rma/rma.pdf', $content);

    // $url = storage_path('app/public/rma/rma.pdf');
    // return $url;
    // return $pdf->download('rmaform.pdf');
    $path = public_path('rma/rma.pdf');
    file_put_contents($path, $content);
    return url('rma/rma.pdf');
  }

  public function refundedTrackingStatuses()
  {
    $TrackingStatus = TrackingStatus::select('id', 'trackingcode', 'refunded')->whereNotIn('trackingcode', function ($query) {
      $query->select('trackingcode')->from('easypost_webhooks');
    })->where(function ($query) {
      return $query->where('refunded', null)->orWhere('refunded', '');
    })->where('trackingcode', '!=', '')->get();
    $count = 0;
    // return $TrackingStatus;
    foreach ($TrackingStatus as $Status) {
      TrackingStatus::where('id', $Status->id)->update(['refunded' => 'refunded']);
      $count = $count + 1;
    }
    return 'Refunded: ' . $count;
    // $query = "select `trackingcode` from `tracking_statuses` where `trackingcode` not in (select `trackingcode` from `easypost_webhooks`) group by `trackingcode`";
  }

  public function refundedVendors()
  {
    $vendors = Vendor::select('id', 'trackingcode', 'refunded')->whereNotIn('trackingcode', function ($query) {
      $query->select('trackingcode')->from('easypost_webhooks');
    })->where(function ($query) {
      return $query->where('refunded', null)->orWhere('refunded', '');
    })->where('trackingcode', '!=', '')->get();
    // return $vendors;
    $count = 0;
    foreach ($vendors as $vendor) {
      Vendor::where('id', $vendor->id)->update(['refunded' => 'refunded']);
      $count = $count + 1;
    }
    return 'Refunded: ' . $count;
    // $query = "select `trackingcode` from `vendors` where `trackingcode` not in (select `trackingcode` from `easypost_webhooks`) and `trackingcode` != ? group by `trackingcode`";
  }

  public function AdminEmails(Request $request)
  {
    // return $request->all();
    $account = Account::with('customermetadata')->find($request->account_id);

    if ($account->customermetadata['email']) {
      $email = $account->customermetadata['email'];
      $itempurchased = $account->itempurchased;
      $customervin = $account->customervin;
      $pricepaid = $account->pricepaid;
      $wrongpart = $request->wrongpart;
      $pricedecrease = '';
      if($request->pricedecrease != ''){
        $pricedecrease = $request->pricedecrease;
      }
      $correctvin = $request->correctvin;
      $emailTemplate = $request->emailTemplate;
      if ($emailTemplate == 'mforv') {
        $teplate = 'message-for-vin';
        $subject = 'Message for VIN';
      }
      if ($emailTemplate == 'ivin') {
        $teplate = 'invalid-vin';
        $subject = 'Invalid VIN';
      }
      if ($emailTemplate == 'diffv') {
        $teplate = 'different-vin';
        $subject = 'Different VIN';
      }
      if ($emailTemplate == 'wpnpd') {
        $teplate = 'wrong-part-no-price-difference';
        $subject = 'Wrong part no price difference';
      }
      if ($emailTemplate == 'wppi') {
        $teplate = 'wrong-part-price-increase';
        $subject = 'Wrong part price increase';
      }
      if ($emailTemplate == 'wppd') {
        $teplate = 'wrong-part-price-decrease';
        $subject = 'Wrong part price decrease';
      }
      if ($emailTemplate == 'vpn') {
        $teplate = 'verify-part-number';
        $subject = 'Verify part number';
      }
      if ($emailTemplate == 'dupo') {
        $teplate = 'duplicate-order';
        $subject = 'Duplicate order';
      }

      if (!$this->validateEmail($email))
        return false;

      $data = array(
        'itempurchased' => $itempurchased,
        'customervin' => $customervin,
        'pricepaid' => $pricepaid,
        'wrongpart' => $wrongpart,
        'pricedecrease' => $pricedecrease,
        'correctvin' => $correctvin,
      );
      try {
        Mail::send(
          'emails.' . $teplate,
          $data,
          function ($message) use ($email, $subject) {
            $message->from(getenv('FROM_EMAIL_ADDRESS'), getenv('MAIL_FROM_NAME'));
            $message->to($email)->subject($subject);
          }
        );
      } catch (Exaption $e) {
        $res = [
          'IsSuccess' => false,
          'Message' => $e,
          'TotalCount' => 0,
          'Data' => null
        ];
        return response()->json($res, 200);
      }
    }

    $res = [
      'IsSuccess' => true,
      'Message' => 'Email send.',
      'TotalCount' => 0,
      'Data' => null
    ];
    return response()->json($res, 200);
  }

  protected function validateEmail($email)
  {
    $regexRule = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/';
    preg_match($regexRule, $email, $matched);
    if (count($matched)) return true;

    return false;
  }

  public function shippingListCount(Request $request)
  {
    $start = $request->startDate;
    $end = $request->fromDate;

    if ($request->startDate == "" || $request->fromDate == "") {
      $shippingInfoCount = ShippingTeamLeaderPoint::whereDate('created_at', Carbon::today())
        ->groupBy('username')
        ->count();

      $shippingInfo = ShippingTeamLeaderPoint::select('username', DB::raw('count(*) AS TotalPoints'))
        ->whereDate('created_at', Carbon::today())
        ->groupBy('username')
        ->get();
    } else {
      $shippingInfoCount = ShippingTeamLeaderPoint::whereBetween(DB::raw('date(created_at)'), array($start, $end))
        ->groupBy('username')
        ->count();

      $shippingInfo = ShippingTeamLeaderPoint::select('username', DB::raw('count(*) AS TotalPoints'))
        ->whereBetween(DB::raw('date(created_at)'), array($start, $end))
        ->groupBy('username')
        ->get();
    }

    if ($shippingInfo) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Shipping List.',
        'TotalCount' => $shippingInfoCount,
        'Data' => $shippingInfo
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

}
