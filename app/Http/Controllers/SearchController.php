<?php

namespace App\Http\Controllers;

use App\Account;
use App\User;
use App\CustomerMetaData;
use App\Vendor;
use App\AccountNote;
use App\Inventory;
use App\Core;
use App\TrackingStatus;
use App\ScannerHistorys;
use App\UserAppOption;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\SearchTermsRequest;

class SearchController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    return view('admin/search');
  }

  public function customermetadatastable(Request $request)
  {
    $searchTerm = request('q');
    $pregMatched = [];
    $skey = $searchTerm[0];
    $sbody = substr($searchTerm, 1);
    $customers = [];

    if (strtolower($skey) === 'h' && is_numeric($sbody)) {
      preg_match_all('/\d+/', $searchTerm, $pregMatched);
      if (count($pregMatched)) {
        $searchTerm = implode(",", $pregMatched[0]);
        $customers = CustomerMetaData::search([
          'account_id'
        ], $searchTerm)->get();
      }
    } else if ($searchTerm !== null) {
      $customers = CustomerMetaData::with('account')->search([
        'account_id',
        'shipto',
        'street1',
        'street2',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'phone2',
        'email',
        'email2',
        'level',
        'address',
      ], $searchTerm)->get();
    }

    return DataTables::of($customers)
      ->addColumn('edit', function ($customer) {
        return '<a href="/accounts/'
          . $customer->account_id
          . '/edit" class="btn-sm btn-info"><i class="fa fa-edit"></i></a>';
      })
      ->rawColumns([
        'edit' => 'edit',
      ])
      ->make(true);
  }

  public function accountstable()
  {
    $searchTerm = request('q');
    $pregMatched = [];
    $skey = $searchTerm[0];
    $sbody = substr($searchTerm, 1);
    $accounts = [];

    if (strtolower($skey) === 'h' && is_numeric($sbody)) {
      preg_match_all('/\d+/', $searchTerm, $pregMatched);
      if (count($pregMatched)) {
        $searchTerm = implode(",", $pregMatched[0]);
        $accounts = Account::search([
          'id'
        ], $searchTerm)->get();
      }
    } else if ($searchTerm !== null) {
      $customer = CustomerMetaData::search([
        'account_id',
        'shipto',
        'street1',
        'street2',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'phone2',
        'email',
        'email2',
        'level',
        'address',
      ], $searchTerm)->select('account_id')->get();

      $trackingStatuses = TrackingStatus::search([
        'antitheft',
        'customertrackingnumber',
        'returntrackinginfo',
        'warrantysticker',
      ], $searchTerm)->select('account_id')->get();

      $accountsByCustomer = Account::whereIn('id', $customer);
      $accountsByTracking = Account::whereIn('id', $trackingStatuses);

      $accounts = Account::search([
        'id',
        'accountname',
        'ordertype',
        'trackingnumbertocustomer',
        'accountteam',
        'primaryaccountmanager',
        'secondaryaccountmanager',
        'whomadethesale',
        'secondarysale',
        'datecustomerpurchased',
        'itempurchased',
        'customervin',
        'programmingdetails',
        'pcmhardwaretype',
        'pricepaid',
        'skuprice',
        'computertype',
        'ebayusername',
        'salesrecord',
        'ebayorder_id',
        'magento_id',
        'requireddeliverydate',
        'contestmultiplier',
        'onedayhandling',
        'sticker',
        'fixplugorcase',
        'changelabel',
        'removebracket',
        'wrongpart',
        'shippinginstructions',
        'created_at',
        'updated_at'
      ], $searchTerm)
        ->union($accountsByCustomer)
        ->union($accountsByTracking)
        ->get();
    }

    return DataTables::of($accounts)
      ->addColumn('edit', function ($account) {
        return '<a href="/accounts/'
          . $account->id
          . '/edit" class="btn-sm btn-info"><i class="fa fa-edit"></i></a>';
      })
      ->rawColumns([
        'edit' => 'edit',
      ])
      ->make(true);
  }

  public function vendorstable(Request $request)
  {
    $searchTerm = request('q');
    $pregMatched = [];
    $skey = $searchTerm[0];
    $sbody = substr($searchTerm, 1);
    $vendors = [];

    if (strtolower($skey) === 'h' && is_numeric($sbody)) {
      preg_match_all('/\d+/', $searchTerm, $pregMatched);
      if (count($vendors)) {
        $searchTerm = implode(",", $pregMatched[0]);
        $vendors = Vendor::search([
          'account_id'
        ], $searchTerm)->get();
      }
    } else if ($searchTerm !== null) {
      $vendors = Vendor::search([
        'account_id',
        'purchasedfrom',
        'salespersonstockno',
        'vin',
        'partprice',
        'shippingprice',
        'pickupcharge',
        'trackingnumber'
      ], $searchTerm)->get();
    }

    return DataTables::of($vendors)
      ->addColumn('edit', function ($vendor) {
        return '<a href="/accounts/'
          . $vendor->account_id
          . '/edit" class="btn-sm btn-info"><i class="fa fa-edit"></i></a>';
      })
      ->rawColumns([
        'edit' => 'edit',
      ])
      ->make(true);
  }

  public function accountnotestable(Request $request)
  {
    $searchTerm = request('q');
    $pregMatched = [];
    $skey = $searchTerm[0];
    $sbody = substr($searchTerm, 1);
    $notes = [];

    if (strtolower($skey) === 'h' && is_numeric($sbody)) {
      preg_match_all('/\d+/', $searchTerm, $pregMatched);
      if (count($pregMatched)) {
        $searchTerm = implode(",", $pregMatched[0]);
        $notes = AccountNote::search([
          'account_id'
        ], $searchTerm)->get();
      }
    } else if ($searchTerm !== null) {
      $notes = AccountNote::search(['account_id', 'notes'], $searchTerm)->get();
    }

    return DataTables::of($notes)
      ->addColumn('edit', function ($note) {
        return '<a href="/accounts/'
          . $note->account_id
          . '/edit" class="btn-sm btn-info"><i class="fa fa-edit"></i></a>';
      })
      ->rawColumns([
        'notes' => 'notes',
        'edit' => 'edit',
      ])
      ->make(true);
  }

  public function creditcardstable(Request $request)
  {
    $searchTerm = request('q');
    $pregMatched = [];
    $skey = $searchTerm[0];
    $sbody = substr($searchTerm, 1);
    $credits = [];

    if (strtolower($skey) === 'h' && is_numeric($sbody)) {
      preg_match_all('/\d+/', $searchTerm, $pregMatched);
      if (count($pregMatched)) {
        $searchTerm = implode(",", $pregMatched[0]);
        $credits = Vendor::search([
          'account_id'
        ], $searchTerm)->get();
      }
    } else if ($searchTerm !== null) {
      $credits = Vendor::search([
        'partprice',
        'shippingprice',
        'pickupcharge',
        'creditcard',
      ], $searchTerm)->get();
    }

    return DataTables::of($credits)
      ->addColumn('total', function ($credit) {
        return floatval($credit->pickupcharge) + floatval($credit->shippingprice) + floatval($credit->partprice);
      })
      ->addColumn('edit', function ($credit) {
        return '<a href="/accounts/'
          . $credit->account_id
          . '/edit" class="btn-sm btn-info"><i class="fa fa-edit"></i></a>';
      })
      ->rawColumns([
        'edit' => 'edit',
      ])
      ->make(true);
  }

  public function inventoryCoreSearch(SearchTermsRequest $request)
  {
    $User = User::all();
    $ScannerHistory = ScannerHistorys::orderBy('id', 'desc')->get();
    $searchTerm = $request->input('barcode');
    $searchTerm = str_replace('s', '', $searchTerm);
    $searchTerm = str_replace('S', '', $searchTerm);
    $inventorysearch = Inventory::where('id', $searchTerm)->get();
    $ScannerHistory = ScannerHistorys::where('inventories_id', $searchTerm)->orderBy('id', 'desc')->get();
    $coresearch = Core::search(['id'], $searchTerm)->get();

    return view('admin/scannermatches', ['inventories' => $inventorysearch, 'cores' => $coresearch, 'barcode' => $searchTerm, 'ScannerHistory' => $ScannerHistory, 'Users' => $User]);
  }

  public function scannerHistory(Request $request)
  {
    $searchTerm = $request->input('inventories_id');

    $ScannerHistory = ScannerHistorys::where('inventories_id', $searchTerm)->orderBy('id', 'desc')->get();

    return  $ScannerHistory;
  }

  public function inventory_search(Request $request)
  {
    $searchTerm = $request->input('terms');
    $pregMatched = [];

    if ($searchTerm) {
      if (strtolower($searchTerm[0]) === 's' && is_numeric($searchTerm[1])) {
        preg_match_all('/\d+/', $searchTerm, $pregMatched);
        if (count($pregMatched)) {
          $searchTerm = implode(",", $pregMatched[0]);
          $inventories = Inventory::search([
            'id'
          ], $searchTerm)
            ->where('publish', '1')
            ->get();
        }
      } else {
        $inventories = Inventory::search([
          'title',
          'hardware',
          'software',
          // 'description',
          // 'xyzposition',
          // 'gxxorlsa',
          // 'publish'
        ], $searchTerm)
          ->where('publish', '1')
          ->get();
      }
    } else {
      $inventories = Inventory::where('publish', 1)->orderBy('id', 'desc')->take(100)->get();
    }

    $result = [];

    foreach ($inventories as $inventory) {
      array_push($result, [
        'id' => $inventory->id,
        'title' => $inventory->title,
        'hardware' => $inventory->hardware,
        'software' => $inventory->software,
        'description' => $inventory->description,
        'xyzposition' => $inventory->xyzposition,
        'gxxorlsa' => $inventory->gxxorlsa,
        'publish' => $inventory->publish
      ]);
    }

    return response()->json($result);
  }

  public function core_search(Request $request)
  {
    $searchTerm = $request->input('terms');
    $pregMatched = [];

    if ($searchTerm) {
      if (strtolower($searchTerm[0]) === 's' && is_numeric($searchTerm[1])) {
        preg_match_all('/\d+/', $searchTerm, $pregMatched);
        if (count($pregMatched)) {
          $searchTerm = implode(",", $pregMatched[0]);
          $core = Core::search([
            'id'
          ], $searchTerm)
            ->where('publish', '1')
            ->get();
        }
      } else {
        $core = Core::search([
          'title',
          'hardware',
          'software',
          // 'description',
          // 'xyzposition',
          // 'gxxorlsa',
          // 'publish'
        ], $searchTerm)
          ->where('publish', '1')
          ->get();
      }
    } else {
      $cores = Core::where('publish', '1')->orderBy('id', 'desc')->take(100)->get();
    }

    $result = [];

    foreach ($cores as $core) {
      array_push($result, [
        'id' => $core->id,
        'title' => $core->title,
        'hardware' => $core->hardware,
        'software' => $core->software,
        'description' => $core->description,
        'xyzposition' => $core->xyzposition,
        'gxxorlsa' => $core->gxxorlsa,
        'publish' => $core->publish,
      ]);
    }

    return response()->json($result);
  }

  public function scannerSearch(Request $request)
  {
    $User = User::all();
    $ScannerHistory = ScannerHistorys::orderBy('id', 'desc')->get();
    return view('admin/scanner')->with(['Users' => $User, 'ScannerHistory' => $ScannerHistory]);
  }

  public function shippingSearch()
  {
    $User = User::all();
    return view('admin/shipping')->with(['Users' => $User]);
  }

  public function ShippingSearchResult(Request $request)
  {
    $User = User::all();
    $searchTerm = $request->input('inventory_id');
    $userId = $request->input('user_id');
    $searchTerm = str_replace('s', '', $searchTerm);
    $searchTerm = str_replace('S', '', $searchTerm);
    $Shipping = UserAppOption::where('inventory_id', $searchTerm)->where('user_id', $userId)->get();
    return response()->json([
      'IsSuccess' => true,
      'TotalCount' => 0,
      'Data' => ['Shipping' => $Shipping]
    ], 200);
  }
}
