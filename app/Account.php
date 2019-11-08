<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
	protected $guarded = ['id'];

	public function customermetadata()
	{
		return $this->hasOne('App\CustomerMetaData');
	}

	public function rmaformdata()
	{
		return $this->hasMany('App\RmaForms')->orderBy('id', 'desc');
	}

	public function orderstatus()
	{
		return $this->hasMany('App\OrderStatus');
	}

	public function vendor()
	{
		return $this->hasMany('App\Vendor');
	}

	public function accountnote()
	{
		return $this->hasMany('App\AccountNote');
	}

	public function programmingentry()
	{
		return $this->hasMany('App\ProgrammingEntry')->orderBy('id', 'desc');
	}

	public function addCustomerMetaData($customermetadata, $update = false)
	{
		if ($update) {
			$this->customermetadata()->update($customermetadata);
		} else {
			$this->customermetadata()->create($customermetadata);
		}
	}

	public function addOrderStatus($orderstatus)
	{
		if (array_filter($orderstatus)) {
			foreach ($orderstatus as $item) {
				$this->orderstatus()->create([
					'role_name' => $item->rolename,
					'orderstatus' => $item->orderstatus,
					'orderstatuscustom' => $item->orderstatuscustom,
					'created_by' => auth()->user()->username,
					'team' => $item->team,
				]);
			}
		}
	}

	public function trackingstatus()
	{
		return $this->hasMany('App\TrackingStatus');
	}

	public function trackingstatusLast()
	{
		return $this->hasMany('App\TrackingStatus')->latest();	
	}
}
