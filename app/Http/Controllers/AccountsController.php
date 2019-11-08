<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountManagerPoint;
use App\AccountNote;
use App\CustomerMetaData;
use App\DocumentsImages;
use App\PartInformation;
use App\ProgrammingEntry;
use App\SecondaryManager;
use App\JunkyardAddress;
use App\TrackingStatus;
use App\RmaForms;
use App\User;
use App\Role;
use App\Vendor;
use App\VendorTracking;
use App\OrderStatus;
use App\Pingemployees;
use App\StockInformation;
use App\ShippingTeamLeaderPoint;
use App\Http\Resources\CustomerMetaDataResource;
use App\Http\Resources\TrackingStatusResource;
use App\Http\Resources\VendorResource;
use App\Http\Resources\ProgrammingEntryResource;
use File;
use function GuzzleHttp\json_encode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Yajra\DataTables\DataTables;
use \Milon\Barcode\DNS1D;
use Mail;
use Illuminate\Support\Facades\DB;
use PHPUnit\Runner\Exception;
use Illuminate\Support\Arr;

class AccountsController extends Controller
{
  protected $emailInfo = [
    'shipment' => [
      'status'  => 'SHIPPING',
      'url'     => 'emails.shipped',
      'subject' => 'Your unit is being shipped',
      'role'    => 'shipping',
      'order'   => 'Email for shipping sent to customer',
    ],
    'program' => [
      'status'  => 'PROGRAMMING',
      'url'     => 'emails.programmed',
      'subject' => 'Your unit is being programmed',
      'role'    => 'programming',
      'order'   => 'Email for programming sent to customer',
    ],
  ];

  protected function getPriceByRegEx($src)
  {
    preg_match('/\d+\.+\d+|\d+/', $src, $matched);
    if (count($matched)) {
      return floatval($matched[0]);
    }
    return 0;
  }

  protected function validateEmail($email)
  {
    $regexRule = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/';
    preg_match($regexRule, $email, $matched);
    if (count($matched)) return true;

    return false;
  }

  protected function sendEmailToCustomer($account, $type)
  {
    if ($account->customermetadata['email']) {
      $email = $account->customermetadata['email'];

      if (!$this->validateEmail($email)) return false;

      $data = array(
        'customer' => $account->customermetadata,
        'account' => $account,
        'preheader' => 'Your order status is updated!',
        'status' => $this->emailInfo[$type]['status'],
      );
      try {
        Mail::send(
          $this->emailInfo[$type]['url'],
          $data,
          function ($message) use ($email, $type) {
            $message->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
            $message->to($email)->subject($this->emailInfo[$type]['subject']);
          }
        );
      } catch (Exaption $e) {
        return false;
      }
    }
    $account->orderstatus()->create([
      'role_name' => $this->emailInfo[$type]['role'],
      'orderstatuscustom' => $this->emailInfo[$type]['order'],
      'created_by' => auth()->user()->username,
    ]);

    return true;
  }

  protected function calculatePricePaid($account, $customer)
  {
    if ($customer) {
      $inNY = $customer->state == 'NY' ? true : false;
      $isFixedTax = $account->ordertype == 'eBay- fs1inc' || $account->ordertype == 'Website' ? true : false;
      $customerLevel = $customer->level;

      if ($account->skuprice != 0 && $account->pricepaid == 0) {
        $taxes = 0;
        $discount = 0;
        if ($inNY && !$isFixedTax && $account->resale_number) {
          $taxes = $account->skuprice * 0.0875;
        }

        if (strpos($customerLevel, "Plus") !== false) {
          $discount = $account->skuprice * 0.05;
        } elseif (strpos($customerLevel, "Premier") !== false) {
          $discount = $account->skuprice * 0.1;
        } elseif (strpos($customerLevel, "Platinum") !== false) {
          $discount = $account->skuprice * 0.15;
        } else {
          $discount = 0;
        }

        $finalPrice = $account->skuprice + $taxes;
        $finalPrice -= $discount;
        $finalPrice += floatval($account->adjustment);

        return $finalPrice;
      }
    }
    return 0;
  }

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    return view('admin.accounts.index');
  }

  public function accountsindextable()
  {
    $accounts = Account::select(
      'id',
      'accountname',
      'primaryaccountmanager',
      'accountteam',
      'created_at',
      'ordertype',
      'itempurchased',
      'datecustomerpurchased',
      'secondaryaccountmanager'
    );

    return DataTables::of($accounts)
      ->addColumn('edit', function ($account) {
        return '<a href="/accounts/'
          . $account->id
          . '/edit" class="btn-sm btn-info"><i class="fa fa-edit"></i></a>';
      })
      ->rawColumns([
        'edit' => 'edit'
      ])
      ->make(true);
  }

  public function print()
  {
    return view('admin.accounts.print');
  }

  public function massPrint(Request $request)
  {
    // return $request->all();
    // die;
    $text = trim($_POST['accounts']);
    $textAr = explode("\n", $text);
    $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
    $accounts = [];
    foreach ($textAr as $line) {
      $account = substr($line, 1);
      array_push($accounts, $account);
    }
    return view('admin.accounts.print-all')->with(['accounts' => $accounts]);
  }

  public function quotes()
  {
    return view('admin.accounts.quotes');
  }

  public function accountsquotestable()
  {
    $sdate = $_GET['sdate'];
    $edate = $_GET['edate'];
    $user = $_GET['user'];
    $team = $_GET['team'];
    $date = \Carbon\Carbon::today()->subDays(7);
    $accounts = Account::select(
      'id',
      'accountname',
      'primaryaccountmanager',
      'accountteam',
      'created_at',
      'ordertype',
      'itempurchased',
      'datecustomerpurchased',
      'secondaryaccountmanager',
      'created_by'
    )->where('ordertype', 'Quote');
    if ($sdate != '' && $edate != '') {
      $accounts->where('created_at', '>=', $sdate)->where('created_at', '<=', $edate);
    } else {
      $accounts->where('created_at', '>=', $date);
    }
    if ($user != '') {
      $accounts->where('created_by', '>=', $user);
    }
    if ($team != '') {
      $accounts->where('accountteam', '>=', $team);
    }

    return DataTables::of($accounts)
      ->addColumn('edit', function ($account) {
        return '<a href="/accounts/'
          . $account->id
          . '/edit" class="btn-sm btn-info"><i class="fa fa-edit"></i></a>';
      })
      ->rawColumns([
        'edit' => 'edit'
      ])
      ->make(true);
  }

  public function create()
  {
    $accountusers = User::whereIn('role', [1, 2])
      ->orderBy('username', 'asc')
      ->get();

    $user = auth()->user();
    $role = Role::find($user->role);

    return view('admin/accounts/create', [
      'accountusers' => $accountusers,
      'creditcard' => $user->creditcard,
      'role' => $user->role,
      'rolename' => $role->name,
    ]);
  }

  public function store(Request $request)
  {
    // Create a new account
    $account = Account::create(
      request([
        'accountname',
        'accountteam',
        'customervin',
        'primaryaccountmanager',
        'secondaryaccountmanager',
        'ordertype',
        'trackingnumbertocustomer',
        'whomadethesale',
        'secondarysale',
        'datecustomerpurchased',
        'ebayusername',
        'requireddeliverydate',
        'contestmultiplier',
        'programmingdetails',
        'salesrecord',
        'docusign',
        'magento_id',
        'itempurchased',
        'pcmhardwaretype',
        'computertype',
        'pricepaid',
        'skuprice',
        'adjustment',
        'reason',
        'resale_number',
        'onedayhandling',
        'sticker',
        'fixplugorcase',
        'changelabel',
        'removebracket',
        'wrongpart',
        'prog_mods',
        'shippinginstructions',
      ])
    );
    $account->created_by = auth()->user()->username;
    if (!$account->datecustomerpurchased) {
      $account->datecustomerpurchased = date('Y-m-d', time());
    }
    $account->save();

    if ($request->input('customervin')) {
      $accountVIN = Account::where('id', '!=', $account->id)->where('customervin', $request->input('customervin'))->first();
      if ($accountVIN) {
        $Pingemployees = new Pingemployees();
        $Pingemployees->user_name = $account->created_by;
        $Pingemployees->send_to = auth()->user()->username;
        $Pingemployees->account_id = $accountVIN->id;
        $Pingemployees->note = 'Possible Quote Already In Hydra2';
        $Pingemployees->save();
      }
    }
    if ($request->input('ispartinformation')) {
      PartInformation::create([
        'item_purchased' => $request->input('itempurchased'),
        'pcm_hw' => $request->input('pcmhardwaretype'),
        'computer_type' => $request->input('computertype'),
        'price_paid' => $request->input('pricepaid'),
      ]);
    }
    // add customer meta data
    $account->addCustomerMetaData(json_decode($request->input('customerMeta'), true));
    if (!$account->customermetadata->level) {
      $customer = $account->customermetadata()->update([
        'level' => 'Normal',
      ]);
    }

    // add order status
    $account->addOrderStatus(json_decode(
      $request->input('orderstatus')
    ));

    // Add a new vendor
    $vendor = $request->input('vendor');
    if ($vendor) {
      $vendor = json_decode($vendor, true);

      $newVendor = $account->vendor()->create(Arr::except($vendor, [
        'id', 'tracking', 'junkyardAddress',
      ]));
      $newVendor->tracking()->create($vendor['tracking']);
      $newVendor->junkyardAddress()->create($vendor['junkyardAddress']);
      if ($account->ordertype != 'stock') {
        $this->addAccountManagerPoint($account, $newVendor);
      }
    }

    if ($request->input('accountnotes')) {
      $account_notes = AccountNote::create([
        'account_id' => $account->id,
        'username' => auth()->user()->username,
        'team' => $account->accountteam,
        'notes' => $request->input('accountnotes'),
      ]);

      $account_notes->save();
    }
    if ($account->whomadethesale != '' || $account->secondarysale != '') {
      $this->addWhomadeTheSalePoint($account);
    }
    //check if image exist
    if ($request->hasFile('image')) {
      $images = $request->file('image');

      //setting flag for condition
      $org_img = $thm_img = true;

      // create new directory for uploading image if doesn't exist
      if (!File::exists('public/images/originals/')) {
        $org_img = File::makeDirectory('public/images/originals/', 0777, true);
      }
      if (!File::exists('public/images/thumbnails/')) {
        $thm_img = File::makeDirectory('public/images/thumbnails', 0777, true);
      }

      // loop through each image to save and upload
      foreach ($images as $key => $image) {
        //create new instance of Photo class
        $newPhoto = new DocumentsImages();
        //get file name of image  and concatenate with 4 random integer for unique
        $filename = rand(1111, 9999) . time() . '.' . $image->getClientOriginalExtension();
        //path of image for upload
        $org_path = 'public/images/originals/' . $filename;
        $thm_path = 'public/images/thumbnails/' . $filename;

        // $newPhoto->image     = 'public/images/originals/'.$filename;
        // $newPhoto->thumbnail = 'public/images/thumbnails/'.$filename;

        //don't upload file when unable to save name to database
        if (!$newPhoto->save()) {
          return false;
        }

        // upload image to server
        if (($org_img && $thm_img) == true) {

          Image::make($image)->fit(900, 500, function ($constraint) {
            $constraint->upsize();
          })->save($org_path);

          Image::make($image)->fit(270, 160, function ($constraint) {
            $constraint->upsize();
          })->save($thm_path);
        }

        $Documents = new DocumentsImages([
          'account_id' => $account->id,
          'image' => $filename,
          'thumbnail' => $filename,
          'description' => $request->input('description'),
          'created_at' => now(),
        ]);

        $Documents->save();
      }
    }

    return redirect('accounts/' . $account->id . '/edit');
  }

  public function show()
  {
    return view('admin/accounts/edit');
  }

  public function edit($id)
  {
    $barcode = '<img src="data:image/png;base64,'
      . DNS1D::getBarcodePNG(strval($id), "C128")
      . '" alt="barcode" class="m-0 p-0" />';

    // get an account by ID
    $account = Account::find($id);
    if ($account == null) {
      abort(404);
    }

    $customer = $account->customermetadata;
    if (isset($customer) && !$customer->level) {
      $customer->level = 'Normal';
      $customer->save();
    }

    $customerData = new CustomerMetaDataResource($account->customermetadata);
    // get Order statuses by account
    $orderstatuses = $account
      ->orderstatus()
      ->orderBy('id', 'DESC')
      ->take(5)
      ->get();

    // get Admin & AccountManager
    $accountusers = User::whereIn('role', [1, 2])
      ->orderBy('username', 'asc')
      ->get();
    // get authrized user's role
    $role = Role::find(auth()->user()->role);

    $user_name = auth()->user()->username;
    $SecondaryManager = SecondaryManager::where('secondary_manager', $user_name)->count();
    $AccountNote = AccountNote::where('account_id', $id)->count();
    $IsPoints = false;
    if ($SecondaryManager > 0 && $AccountNote > 0) {
      $IsPoints = true;
    }
    // get Vendors by account id
    $vendors = VendorResource::collection($account->vendor);
    // get Tracking statuses by account id
    $trackingStatuses = TrackingStatusResource::collection($account->trackingstatus);
    // get Last Shipped Date
    $lastOutBound = $account->trackingstatus()
      ->where('direction', 'OutBound')
      ->orderBy('id', 'desc')
      ->first();
    $dateShipped = $lastOutBound ? $lastOutBound->dateshippedtocustomer : null;
    // get Last Customer Tracking Number
    $lastTracking = $account->trackingstatus()
      ->orderBy('id', 'desc')
      ->first();
    $customerTrackingNumber = $lastTracking ? $lastTracking->customertrackingnumber : null;

    // get account manager point
    $account_points = AccountManagerPoint::where([
      'account_id' => $account->id,
      'type' => 'primarypoints',
    ])->latest()->get();

    $primary_points = 0;
    if (count($account_points)) {
      $primary_points = $account_points[0]->price_paid
        - $account_points[0]->part_price
        - $account_points[0]->shipping_price
        - $account_points[0]->pickup_charge;

      $primary_points = $primary_points < 80 ? -75 : $primary_points;
    } else {
      $primary_points = '';
    }

    // get Documents
    $documents = DocumentsImages::all();
    $StockInformation = StockInformation::with('StockMaster')->where('account_id', $account->id)->first();

    // Calculate price if it was not fixed.
    // $account->pricepaid = round($this->calculatePricePaid($account, $customerData), 2);
    // $account->save();

    return view('admin.accounts.edit', [
      'barcode' => $barcode,
      'account' => $account,
      'customermetadata' => $customerData,
      'orderstatuses' => json_encode($orderstatuses),
      'programmingentries' => $account->programmingentry,
      'vendors' => json_encode($vendors),
      'trackings' => $trackingStatuses,
      'accountnotes' => $account->accountnote,
      'documents' => $documents,
      'shippedDate' => $dateShipped,
      'customerTrackingNumber' => $customerTrackingNumber,
      'accountusers' => $accountusers,
      'rolename' => $role->name,
      'primary_points' => $primary_points,
      'IsPoints' => $IsPoints,
      'StockInformation' => $StockInformation
    ]);
  }

  public function update(Request $request, Account $account)
  {
    // return $request->all();
    // return $request->pricepaid; die;
    $account_data = request([
      'accountname',
      'ordertype',
      'trackingnumbertocustomer',
      'accountteam',
      'whomadethesale',
      'secondarysale',
      'primaryaccountmanager',
      'secondaryaccountmanager',
      'datecustomerpurchased',
      'requireddeliverydate',
      'customervin',
      'programmingdetails',
      'ebayusername',
      'salesrecord',
      'magento_id',
      'ebayorder_id',
      'contestmultiplier',
      'itempurchased',
      'pcmhardwaretype',
      'computertype',
      'docusign',
      'pricepaid',
      'skuprice',
      'adjustment',
      'reason',
      'resale_number',
      'onedayhandling',
      'sticker',
      'fixplugorcase',
      'changelabel',
      'removebracket',
      'wrongpart',
      'prog_mods',
      'shippinginstructions',
    ]);

    $account->update($account_data);
    $account->modified_by = auth()->user()->username;
    $account->save();

    $user = auth()->user();
    $Role = Role::find($user->role);

    if ($request->input('ispartinformation') == TRUE) {
      PartInformation::create(
        [
          'item_purchased' => $request->input('itempurchased'),
          'pcm_hw' => $request->input('pcmhardwaretype'),
          'computer_type' => $request->input('computertype'),
          'price_paid' => $request->input('pricepaid'),
        ]
      );
    }

    if ($request->input('customervin')) {
      $accountVIN = Account::where('id', '!=', $account->id)->where('customervin', $request->input('customervin'))->first();
      if ($accountVIN) {
        $Pingemployees = new Pingemployees();
        $Pingemployees->user_name = $account->created_by;
        $Pingemployees->send_to = $user->username;
        $Pingemployees->account_id = $accountVIN->id;
        $Pingemployees->note = 'Possible Quote Already In Hydra2';
        $Pingemployees->save();
      }
    }

    $account->customermetadata()->update([
      'level' => $request->input('customerLevel'),
    ]);

    $account->addOrderStatus(json_decode(
      $request->input('orderstatus')
    ));

    $lastVendor = $account
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

    // add account manager point
    if ($lastVendor) {
      if ($account->ordertype != 'stock') {
        $this->addAccountManagerPoint($account, $lastVendor);
      }
    }
    if ($account->whomadethesale != '' || $account->secondarysale != '') {
      $this->addWhomadeTheSalePoint($account);
    }
    if ($account->ordertype == 'Stock') {
      if ($request->stock_master_id != '') {
        $total = 0;
        if ($request->stock_master_id == 1)
          $total = $account->pricepaid * 40 / 100;
        else if ($request->stock_master_id == 2)
          $total = $account->pricepaid * 30 / 100;
        else if ($request->stock_master_id == 3)
          $total = $account->pricepaid * 25 / 100;
        else if ($request->stock_master_id == 4)
          $total = $account->pricepaid * 15 / 100;
        else
          $total = $account->pricepaid;
        if ($request->stock_lbo)
          $total = $total * 1.5;
        $StockInformation = StockInformation::where('account_id', $account->id)->first();
        if ($StockInformation) {
          $StockInformation->stock_master_id = $request->stock_master_id;
          if ($request->stock_lbo) {
            $StockInformation->stock_lbo = 1;
          } else {
            $StockInformation->stock_lbo = 0;
          }
          $StockInformation->save();
        } else {
          $StockInformation = new StockInformation();
          $StockInformation->account_id = $account->id;
          $StockInformation->stock_master_id = $request->stock_master_id;
          if ($request->stock_lbo) {
            $StockInformation->stock_lbo = 1;
          } else {
            $StockInformation->stock_lbo = 0;
          }
          $StockInformation->save();
        }

        $StockAccountManagerPoint = AccountManagerPoint::where('account_id', $account->id)->where('type', 'stock')->first();
        if ($StockAccountManagerPoint) {
          $StockAccountManagerPoint->account_id = $account->id;
          $StockAccountManagerPoint->user_name = $user->username;
          $StockAccountManagerPoint->team = $user->team;
          // $StockAccountManagerPoint->type = 'stock';
          $StockAccountManagerPoint->price_paid = $account->pricepaid;
          $StockAccountManagerPoint->multiplier = $request->stock_master_id;
          $StockAccountManagerPoint->total = $total;
          $StockAccountManagerPoint->save();
        } else {
          $StockAccountManagerPoint = new AccountManagerPoint();
          $StockAccountManagerPoint->account_id = $account->id;
          $StockAccountManagerPoint->user_name = $user->username;
          $StockAccountManagerPoint->team = $user->team;
          $StockAccountManagerPoint->type = 'stock';
          $StockAccountManagerPoint->price_paid = $account->pricepaid;
          $StockAccountManagerPoint->multiplier = $request->stock_master_id;
          $StockAccountManagerPoint->total = $total;
          $StockAccountManagerPoint->save();
        }
        // return $StockAccountManagerPoint; die;
      }
    }

    $request->validate([
      // 'image' => 'required',
      'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
    ]);

    if ($request->hasFile('image')) {

      $images = $request->file('image');

      //setting flag for condition
      $org_img = $thm_img = true;

      // create new directory for uploading image if doesn't exist
      if (!File::exists('public/images/account/')) {
        $org_img = File::makeDirectory('public/images/account/', 0777, true);
      }
      if (!File::exists('public/images/thumbnails/')) {
        $thm_img = File::makeDirectory('public/images/thumbnails', 0777, true);
      }

      // loop through each image to save and upload
      foreach ($images as $key => $image) {
        //create new instance of Photo class
        $newPhoto = new DocumentsImages();
        //get file name of image  and concatenate with 4 random integer for unique
        $filename = rand(1111, 9999) . time() . '.' . $image->getClientOriginalExtension();
        //path of image for upload

        $org_path = public_path() . '/images/account/' . $filename;
        $thm_path = public_path() . '/images/thumbnails/' . $filename;
        // //don't upload file when unable to save name to database
        // if ( ! $newPhoto->save()) {
        //     return false;
        // }

        // upload image to server
        if (($org_img && $thm_img) == true) {
          Image::make($image)->fit(600, 350, function ($constraint) {
            $constraint->upsize();
          })->save($org_path);

          Image::make($image)->fit(270, 160, function ($constraint) {
            $constraint->upsize();
          })->save($thm_path);
        }

        // $Documents = new DocumentsImages([
        //     'account_id' =>$request->input('modified_by'),
        //     'image' => $filename,
        //     'thumbnail' => $filename,
        //     'description' =>$request->input('description'),
        //     'created_at' => now()
        // ]);

        // $Documents->save();
        echo $request->input('modified_by');
        echo $request->input('description');

        DocumentsImages::create([
          'account_id' => $account->id,
          'image' => $filename,
          'thumbnail' => $filename,
          'description' => $request->input('description'),
          'created_at' => now(),
        ]);
      }
    }

    if ($request->input('accountnotes') !== null) {
      $account_notes = new AccountNote([
        'account_id' => $account->id,
        'username' => auth()->user()->username,
        'team' => $account->accountteam,
        'notes' => $request->input('accountnotes'),
      ]);

      $account_notes->save();
    }

    if ($request->input('orderstatuscustom') !== null) {
      $OrderStatus = new OrderStatus();
      $OrderStatus->account_id = $account->id;
      $OrderStatus->role_name = $Role->name;
      $OrderStatus->orderstatus = '';
      $OrderStatus->orderstatuscustom = $request->orderstatuscustom;
      $OrderStatus->team = $user->team;
      $OrderStatus->created_by = $user->username;
      $OrderStatus->save();
    }

    // add secondary manager
    SecondaryManager::create([
      'account_id' => $account->id,
      'secondary_manager' => auth()->user()->username,
      'team' => auth()->user()->team,
    ]);

    return redirect('accounts/' . $account->id . '/edit');
  }

  public function destroy(Account $account)
  {
    //
  }

  public function get_relation_data()
  {
    $queryString = Input::get('queryString');

    $part_info = PartInformation::select(
      'id',
      'item_purchased',
      'pcm_hw',
      'computer_type',
      'price_paid'
    )
      ->where(
        'item_purchased',
        'like',
        '%' . $queryString . '%'
      )
      ->get();

    return response()->json($part_info);
  }

  public function get_shop_name()
  {
    $queryString = Input::get('queryString');

    $data = CustomerMetaData::select(
      'id',
      'shopname'
    )->where(
      'shopname',
      'like',
      '%' . $queryString . '%'
    )
      ->get();

    return response()->json($data);
  }

  public function get_customer_name()
  {
    $queryString = Input::get('queryString');

    $data = CustomerMetaData::select(
      'id',
      'shipto'
    )->where(
      'shipto',
      'like',
      '%' . $queryString . '%'
    )
      ->get();

    return response()->json($data);
  }

  public function get_shop_name_vendor()
  {
    $queryString = Input::get('queryString');

    $data = JunkyardAddress::select(
      'id',
      'shopname'
    )->where(
      'shopname',
      'like',
      '%' . $queryString . '%'
    )
      ->get();

    return response()->json($data);
  }

  public function get_metadata(Request $request)
  {
    $CustomerMetaData = CustomerMetaData::find($request->id);
    return response()->json($CustomerMetaData);
  }

  public function get_metadata_vendor(Request $request)
  {
    $CustomerMetaData = JunkyardAddress::find($request->id);
    return response()->json($CustomerMetaData);
  }

  public function usernameIndex()
  {
    return view('admin/accounts/misc/usernameindex');
  }

  public function addAccountManagerPoint($account, $last_vendor)
  {
    $account_team = $account->accountteam;
    $user_name = auth()->user()->username;

    $price_paid = doubleval($account->pricepaid);
    $part_price = doubleval($last_vendor['partprice']);
    $shipping_price = doubleval($last_vendor['shippingprice']);
    $pickup_charge = doubleval($last_vendor['pickupcharge']);
    $stock_number = $last_vendor['salespersonstockno'];
    $in_stock = $last_vendor['in_stock'];
    $multiplier = intval($account->contestmultiplier);
    $total = $price_paid - ($part_price + $shipping_price + $pickup_charge); // count total points

    $shipping_date = $account->trackingstatus()
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
    /* if ($account->secondaryaccountmanager) {
      $SecondaryManager = SecondaryManager::where('secondary_manager', $user_name)->count();
      $AccountNote = AccountNote::where('username', $user_name)->count();
      if ($SecondaryManager > 0 && $AccountNote > 0)
        $total = 100;
      // Update record if below three conditions match, else create new record
      AccountManagerPoint::updateOrCreate(
        [
          'account_id' => $account->id,
          'user_name' => $user_name,
          'type' => 'secondaryaccountmanager',
        ],
        [
          'team' => $account_team,
          'seller' => $account->secondaryaccountmanager,
          'price_paid' => $price_paid,
          'part_price' => $part_price,
          'shipping_price' => $shipping_price,
          'pickup_charge' => $pickup_charge,
          'stock_number' => $stock_number,
          'multiplier' => $multiplier,
          'shipping_date' => $dateshippedtocustomer,
          'total' => $total,
        ]
      );
    } */

    // primary account manager points save/update
    if ($account->primaryaccountmanager) {
      $total = $price_paid - ($part_price + $shipping_price + $pickup_charge); // count total points
      if ($total < 80) {
        $total = -75; // if total points <(less then) 80 then total points = -75
      }
      if ($in_stock == 1) {
        $total = 55;  // if purchasedfrom == 'InStock' then total points = 55 Fix points
      }
      // Update record if below three conditions match, else create new record
      AccountManagerPoint::updateOrCreate(
        [
          'account_id' => $account->id,
          'type' => 'primarypoints',
        ],
        [
          'user_name' => $user_name,
          'team' => $account_team,
          'seller' => $account->primaryaccountmanager,
          'price_paid' => $price_paid,
          'part_price' => $part_price,
          'shipping_price' => $shipping_price,
          'pickup_charge' => $pickup_charge,
          'stock_number' => $stock_number,
          'multiplier' => $multiplier,
          'shipping_date' => $dateshippedtocustomer,
          'total' => $total,
        ]
      );
    }
  }

  public function addWhomadeTheSalePoint($account)
  {
    $account_team = $account->accountteam;
    // $user_name = auth()->user()->username;
    $price_paid = doubleval($account->pricepaid);
    if ($account->whomadethesale != '' && $account->secondarysale != '') {
      $price_paid = $price_paid / 2;
    }
    if ($account->whomadethesale != '') {
      AccountManagerPoint::updateOrCreate(
        [
          'account_id' => $account->id,
          'type' => 'whomadethesale',
        ],
        [
          'user_name' => $account->whomadethesale,
          'team' => $account_team,
          'seller' => $account->whomadethesale,
          'price_paid' => $price_paid,
          'total' => $price_paid,
        ]
      );
    }

    if ($account->secondarysale != '') {
      AccountManagerPoint::updateOrCreate(
        [
          'account_id' => $account->id,
          'type' => 'secondarysale',
        ],
        [
          'user_name' => $account->secondarysale,
          'team' => $account_team,
          'seller' => $account->secondarysale,
          'price_paid' => $price_paid,
          'total' => $price_paid,
        ]
      );
    }
  }

  public function fetch_states()
  {
    $jsonString = file_get_contents(base_path('resources/meta/states_titlecase.json'));
    return response()->json($jsonString);
  }

  public function get_all_order_status()
  {
    $account = Account::find(Input::get('id'));
    if ($account) {
      $all_status = $account->orderstatus()
        ->orderBy('id', 'DESC')
        ->get();

      return response()->json($all_status);
    }
  }

  public function AddInstock(Request $request)
  {
    $UserId = auth()->user()->id;
    $vendor = new Vendor([
      'account_id' => $request->account_id,
      'purchasedfrom' => 'In Stock',
      'datepurchased' => $request->datePulled,
      'salespersonstockno' => $request->sId,
      'returnreason' => $request->returnReason,
    ]);
    $vendor->save();
    VendorTracking::create([
      'vendor_id' => $vendor->id,
      'refunded' => 0,
      'spoke_to' => 0,
      'confirmation' => 0,
    ]);
    if ($vendor) {
      $instockList = Vendor::where('account_id', $request->account_id)->get();
      $instockData = [];
      foreach ($instockList as $instock) {
        $data = array(
          'account_id' => $instock->account_id,
          'salespersonstockno' => $instock->salespersonstockno,
          'returnreason' => $instock->returnreason,
          'purchasedfrom' => $instock->purchasedfrom,
          'datepurchased' => $instock->datepurchased
        );
        array_push($instockData, $data);
      }
      $instockData = json_encode($instockData);
      return $instockData;
    } else {
      return "false";
    }
  }

  public function GetDocumentDetails(Request $request)
  {
    if (isset($request->account_id) && $request->account_id != '') {
      $Doc = DocumentsImages::where('account_id', $request->account_id)->get();
      $data = [];
      if ($Doc) {
        return $Doc;
      } else {
        return $data;
      }
    } else {
      return "false";
    }
  }

  public function UpdateDocumentDetails(Request $request)
  {
    // return $request->all();
    // die;
    // Images uploadile_get_
    // $image = $request->file('image')->getClientOriginalExtension();
    // return $image;
    $request->validate([
      'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
    //check if image exist
    if ($request->hasFile('image')) {

      //setting flag for condition
      $org_img = $thm_img = true;
      // create new directory for uploading image if doesn't exist
      if (!File::exists('public/images/account/')) {
        $org_img = File::makeDirectory('public/images/account/', 0777, true);
      }
      if (!File::exists('public/images/thumbnails/')) {
        $thm_img = File::makeDirectory('public/images/thumbnails', 0777, true);
      }

      $image = $request->file('image');

      $newPhoto = new DocumentsImages();
      //get file name of image  and concatenate with 4 random integer for unique
      $filename = rand(1111, 9999) . time() . '.' . $image->getClientOriginalExtension();
      //path of image for upload

      $org_path = public_path() . '/images/account/' . $filename;
      $thm_path = public_path() . '/images/thumbnails/' . $filename;
      // //don't upload file when unable to save name to database

      // upload image to server
      if (($org_img && $thm_img) == true) {
        Image::make($image)->fit(600, 350, function ($constraint) {
          $constraint->upsize();
        })->save($org_path);

        Image::make($image)->fit(270, 160, function ($constraint) {
          $constraint->upsize();
        })->save($thm_path);
      }
    }
    $Documents = DocumentsImages::find($request->id);
    // return  $Documents;
    // $Documents->account_id = $request->account_id;
    if ($request->hasFile('image')) {
      $DocImage = public_path('images/account/') . '/' . $Documents->image;
      $DocThumbnail = public_path('images/thumbnails/') . '/' . $Documents->thumbnail;
      if (file_exists($DocImage)) {
        @unlink($DocImage);
      }
      if (file_exists($DocThumbnail)) {
        @unlink($DocThumbnail);
      }
      $Documents->image = $filename;
      $Documents->thumbnail = $filename;
    }
    $Documents->description = $request->input('description');
    $Documents->updated_at = now();
    $Documents->save();
    if ($Documents) {
      return "true";
    } else {
      return "false";
    }
  }

  public function DeleteDocumentDetails(Request $request)
  {
    $DocumentId = $request->id;
    $Doc = DocumentsImages::find($DocumentId);
    $DocImage = public_path('images/account/') . '/' . $Doc->image;
    $DocThumbnail = public_path('images/thumbnails/') . '/' . $Doc->thumbnail;
    if (file_exists($DocImage)) {
      @unlink($DocImage);
    }
    if (file_exists($DocThumbnail)) {
      @unlink($DocThumbnail);
    }
    $delete = $Doc->delete();
    if ($delete) {
      return 'true';
    } else {
      return 'false';
    }
  }

  public function RMAForm(Request $request)
  {
    $account_id = $request->account_id;
    $form_id = $request->form_id;
    $Acc = Account::find($account_id);
    $MetaData = CustomerMetaData::where('account_id', $account_id)->first();
    $RmaForms = RmaForms::where('id', $form_id)->where('account_id', $account_id)->first();
    // $RmaForms = RmaForms::find(2);
    $street1 = '';
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
      $street1 = $MetaData->street1;
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

    $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('rmaformTemplate.docx'));
    $my_template->setValue('date', date('M d,Y'));
    $my_template->setValue('order', $Acc->id);
    $my_template->setValue('name', $Acc->accountname);
    $my_template->setValue('customerstreet1', $street1);
    $my_template->setValue('customercity', $city);
    $my_template->setValue('customerstate', $state);
    $my_template->setValue('customerzip', $zip);
    $my_template->setValue('accountmanager', $Acc->primaryaccountmanager);
    $my_template->setValue('vin', $Acc->customervin);
    $my_template->setValue('partnumber', $Acc->wrongpart);
    $my_template->setValue('original_problem', $original_problem);
    $my_template->setValue('steps_taken_to_diagnose_problem', $steps_taken_to_diagnose_problem);
    $my_template->setValue('problems_with_part', $problems_with_part);
    $my_template->setValue('steps_taken_to_diagnose_with_part', $steps_taken_to_diagnose_with_part);
    $my_template->setValue('additional_notes', $additional_notes);
    $my_template->setValue('customer_signature', $customer_signature);
    $my_template->setValue('customer_name', $customer_name);
    try {
      $Form = public_path('RMAForm.docx');
      if (file_exists($Form)) {
        @unlink($Form);
      }
      $my_template->saveAs(public_path('RMAForm.docx'));
    } catch (Exception $e) {
      return $e;
    }
    // return  array('file'=>response()->download(public_path('RMAForm.docx')));
    return  array('file' => url('RMAForm.docx'));
  }

  public function RMAFormDownload(Request $request)
  {
    $account_id = $request->account_id;
    $Acc = Account::find($account_id);
    $MetaData = CustomerMetaData::where('account_id', $account_id)->first();
    $street1 = '';
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
      $street1 = $MetaData->street1;
      $city = $MetaData->city;
      $state = $MetaData->state;
      $zip = $MetaData->zip;
    }

    $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('rmaformTemplate.docx'));
    $my_template->setValue('date', date('M d,Y'));
    $my_template->setValue('order', $Acc->id);
    $my_template->setValue('name', $Acc->accountname);
    $my_template->setValue('customerstreet1', $street1);
    $my_template->setValue('customercity', $city);
    $my_template->setValue('customerstate', $state);
    $my_template->setValue('customerzip', $zip);
    $my_template->setValue('accountmanager', $Acc->primaryaccountmanager);
    $my_template->setValue('vin', $Acc->customervin);
    $my_template->setValue('partnumber', $Acc->wrongpart);
    $my_template->setValue('original_problem', $original_problem);
    $my_template->setValue('steps_taken_to_diagnose_problem', $steps_taken_to_diagnose_problem);
    $my_template->setValue('problems_with_part', $problems_with_part);
    $my_template->setValue('steps_taken_to_diagnose_with_part', $steps_taken_to_diagnose_with_part);
    $my_template->setValue('additional_notes', $additional_notes);
    $my_template->setValue('customer_signature', $customer_signature);
    $my_template->setValue('customer_name', $customer_name);
    try {
      $Form = public_path('RMAForm.docx');
      if (file_exists($Form)) {
        @unlink($Form);
      }
      $my_template->saveAs(public_path('RMAForm.docx'));
    } catch (Exception $e) {
      return $e;
    }
    // return  array('file'=>response()->download(public_path('RMAForm.docx')));
    return  array('file' => url('RMAForm.docx'));
  }

  public function RMAFormSendFromEmail(Request $request)
  {
    $account_id = $request->account_id;
    $form_id = $request->form_id;
    $email = $request->email;

    $Acc = Account::find($account_id);
    $MetaData = CustomerMetaData::where('account_id', $account_id)->first();
    $RmaForms = RmaForms::where('id', $form_id)->where('account_id', $account_id)->first();
    $street1 = '';
    $city = '';
    $state = '';
    $zip = '';
    if ($MetaData) {
      $street1 = $MetaData->street1;
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

    $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('rmaformTemplate.docx'));
    $my_template->setValue('date', date('M d,Y'));
    $my_template->setValue('order', $Acc->id);
    $my_template->setValue('name', $Acc->accountname);
    $my_template->setValue('customerstreet1', $street1);
    $my_template->setValue('customercity', $city);
    $my_template->setValue('customerstate', $state);
    $my_template->setValue('customerzip', $zip);
    $my_template->setValue('accountmanager', $Acc->primaryaccountmanager);
    $my_template->setValue('vin', $Acc->customervin);
    $my_template->setValue('partnumber', $Acc->wrongpart);
    $my_template->setValue('original_problem', $original_problem);
    $my_template->setValue('steps_taken_to_diagnose_problem', $steps_taken_to_diagnose_problem);
    $my_template->setValue('problems_with_part', $problems_with_part);
    $my_template->setValue('steps_taken_to_diagnose_with_part', $steps_taken_to_diagnose_with_part);
    $my_template->setValue('additional_notes', $additional_notes);
    $my_template->setValue('customer_signature', $customer_signature);
    $my_template->setValue('customer_name', $customer_name);
    try {
      $Form = public_path('RMAForm.docx');
      if (file_exists($Form)) {
        @unlink($Form);
      }
      $my_template->saveAs(public_path('RMAForm.docx'));
    } catch (Exception $e) {
      return $e;
    }
    // return  array('file'=>response()->download(public_path('RMAForm.docx')));
    // return  array('file'=> url('RMAForm.docx') );
    $pathToFile = public_path('RMAForm.docx');

    $data = array(
      'accountname' => $Acc->accountname,
      'email' => $email
    );
    Mail::send('emails.rmaform', $data, function ($message) use ($pathToFile, $email) {
      $message->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
      $message->to($email)->subject('RMA FORM');
      $message->attach($pathToFile);
    });

    $res = [
      'IsSuccess' => true,
      'Message' => 'RMA FORM was sent to your email.',
      'TotalCount' => 0,
      'Data' => null
    ];
    return response()->json($res, 200);
  }

  public function SecondaryManagers(Request $request)
  {
    $SecondaryManager = SecondaryManager::where('account_id', $request->account_id)->get();
    return $SecondaryManager;
  }

  public function add_part_location(Request $request)
  {
    $account = Account::find($request->id);
    if ($account) {
      $order[0] = (object) [
        'rolename' => $request->data['rolename'],
        'orderstatus' => $request->data['orderstatus'],
        'orderstatuscustom' => $request->data['orderstatuscustom'],
        'team' => auth()->user()->team,
      ];
      $account->addOrderStatus($order);
      return response()->json('success', 200);
    } else {
      return response()->json('faild', 406);
    }
  }

  public function programmingList(Request $request)
  {
    $entries = ProgrammingEntry::getEntriesBetweemDate($request->from, $request->to);

    if ($entries->count()) {
      $entries = $entries->select('username', DB::raw('count(id) as total'))
        ->where('username', '<>', '')
        ->groupBy('username')
        ->paginate(15);
    }

    return response()->json($entries);
  }

  public function programUser(Request $request)
  {
    return view('admin.points.programUser', [
      'user' => $request->name,
      'from' => $request->from,
      'to' => $request->to,
    ]);
  }

  public function programUserList(Request $request)
  {
    $entries = ProgrammingEntry::getEntriesBetweemDate($request->from, $request->to);
    if ($entries->count()) {
      $entries = $entries
        ->where('username', $request->name)
        ->paginate(10);
    }

    return response()->json($entries);
  }

  public function accountManagerList(Request $request)
  {
    $points = AccountManagerPoint::getManagerPoints($request->from, $request->to);

    if ($points->count()) {
      $points = $points
        ->select('user_name', DB::raw('sum(total) as totalPoints'))
        ->where('type', 'primarypoints')
        ->groupBy('user_name')
        ->orderBy('user_name')
        ->get();
    }
    return response()->json($points);
  }

  public function accountManagerListDownload(Request $request)
  {
    $points = AccountManagerPoint::getManagerPoints($request->from, $request->to);

    if ($points->count()) {
      $points = $points
        ->select('user_name', DB::raw('sum(total) as totalPoints'))
        ->where('type', 'primarypoints')
        ->groupBy('user_name')
        ->get();
    }

    return array('data' => $points);
  }

  public function accountManager(Request $request)
  {
    return view('admin.points.accountmanagerpoint', [
      'user' => $request->name,
      'from' => $request->from,
      'to' => $request->to,
    ]);
  }

  public function accountManagerPoints(Request $request)
  {
    $points = AccountManagerPoint::getManagerPoints($request->from, $request->to);

    if ($points->count()) {
      $points = $points
        ->where('user_name', $request->name)
        ->paginate(10);
    }

    return response()->json($points);
  }

  public function accountManagerPointsDownload(Request $request)
  {
    $points = AccountManagerPoint::getManagerPoints($request->from, $request->to);

    if ($points->count()) {
      $points = $points
        ->where('user_name', $request->name)
        ->get();
    }

    return array('data' => $points);
  }

  public function docusignView()
  {
    return view('admin/accounts/docusign/view');
  }

  public function RMAFormPage()
  {
    return view('admin/accounts/rmaform/create');
  }

  public function updateOrCreateVendor(Request $request, Account $account)
  {
    $vendorToUpdate = $account
      ->vendor()
      ->updateOrCreate([
        'id' => $request->input('id'),
      ], $request->except(['id', 'tracking', 'junkyardAddress']));

    $vendorToUpdate
      ->tracking()
      ->updateOrCreate([
        'vendor_id' => $request->input('id'),
      ], $request->input('tracking'));

    $vendorToUpdate
      ->junkyardAddress()
      ->updateOrCreate([
        'vendor_id' => $request->input('id')
      ], $request->input('junkyardAddress'));

    return response()->json(new VendorResource($vendorToUpdate));
  }

  public function updateCustomer(Request $request, Account $account)
  {
    $customer = $account->customermetadata;
    $customer->update($request->all());

    return response()->json(new CustomerMetaDataResource($customer));
  }

  public function updateOrCreateTracking(Request $request, Account $account)
  {
    $trackingUpdated = $account
      ->trackingstatus()
      ->updateOrCreate([
        'id' => $request->input('id')
      ], $request->except('id'));

    if ($request->input('id') == -1 && $trackingUpdated->dateshippedtocustomer) {
      $this->sendEmailToCustomer($account, 'shipment');
    }

    if ($trackingUpdated->deliverydatetocustomer != NULL && (auth()->user()->email == 'mark.j@mintt.com' || auth()->user()->email == 'kevin.g@mintt.com')) {
      $ShippingTeamLeaderPoint = ShippingTeamLeaderPoint::updateOrCreate([
        'tracking_status_id' => $trackingUpdated->id
      ], [
        'account_id' => $trackingUpdated->account_id,
        'username' => auth()->user()->username
      ]);
    }

    return response()->json(new TrackingStatusResource($trackingUpdated));
  }

  public function updateOrCreateEntry(Request $request, Account $account)
  {
    $entryUpdated = $account
      ->programmingentry()
      ->updateOrCreate([
        'id' => $request->input('id'),
      ], $request->except('id'));

    $entryUpdated->username = auth()->user()->username;
    $entryUpdated->save();

    if ($request->input('id') == -1) {
      $entryUpdated->username = auth()->user()->username;
      if ($entryUpdated->entrytype == 'Programmed') {
        $this->sendEmailToCustomer($account, 'program');
      }
    }

    return response()->json(new ProgrammingEntryResource($entryUpdated));
  }

  public function customerVINMatches(Request $request)
  {
    $customervin = $request->input('customervin');
    $account_id = $request->input('account_id');
    $accountVIN = Account::select('id')->where('id', '!=', $account_id)->where('customervin', $customervin)->where('ordertype', 'Quote')->first();
    if ($accountVIN && $customervin != '') {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Record match.',
        'TotalCount' => 0,
        'Data' => $accountVIN
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'No mathes found.',
        'TotalCount' => 0,
        'Data' => null
      ], 200);
    }
  }

  public function deleteOrderStatus(Request $request)
  {
    $orderstatuses = OrderStatus::find($request->id);
    if ($orderstatuses) {
      $account = Account::find($orderstatuses->account_id);
      $orderstatuses->delete();
      $all_status = $account->orderstatus()
        ->orderBy('id', 'DESC')
        ->get();
      return response()->json($all_status);
    } else {
      return false;
    }
  }
}
