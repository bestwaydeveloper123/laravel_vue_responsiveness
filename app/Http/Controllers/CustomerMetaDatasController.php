<?php

namespace App\Http\Controllers;

use App\CustomerMetaData;
use Illuminate\Http\Request;

class CustomerMetaDatasController extends Controller
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
        //
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
        dd('hi');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerMetaData  $customerMetaData
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerMetaData $customerMetaData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerMetaData  $customerMetaData
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerMetaData $customerMetaData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerMetaData  $customerMetaData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerMetaData $customerMetaData)
    {
        dd('hi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerMetaData  $customerMetaData
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerMetaData $customerMetaData)
    {
        //
    }
}
