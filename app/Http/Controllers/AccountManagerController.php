<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountManagerPoint;
use App\Account;

class AccountManagerController extends Controller
{
		public function __construct()
		{
				$this->middleware('auth');
		}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accountmanager = AccountManagerPoint::paginate(3);

        return view('admin/points/accountmanager', ['account_manager' => $accountmanager]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function download() {

        $file_name = 'account_manager_' . \date('Y_m_d') . '.csv';

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $file_name,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $accountmanager = AccountManagerPoint::all();
        $columns = array('USER NAME', 'Team', 'Type', 'Sale Amount', 'Purchase Price', 'Shipping',
                    'Pickup', 'Description', 'Multiplier', 'Points', 'Shipping Date');

        $callback = function() use ($accountmanager, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($accountmanager as $review) {
                fputcsv($file, array(
                    $accountmanager->user_name,
                    $accountmanager->team,
                    $accountmanager->type,
                    $accountmanager->price_paid,
                    $accountmanager->part_price,
                    $accountmanager->shipping_price,
                    $accountmanager->pickup_charge,
                    '',
                    $accountmanager->multiplier,
                    $accountmanager->total,
                    $accountmanager->shipping_date));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function shippingUser($id)
    {
        return view('admin/points/shippingUser')->with(['id' => $id]);
    }
}
