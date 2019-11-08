<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerMetaData extends Model
{

	protected $guarded = ['id'];

	public function account ()
	{
		return $this->belongsTo('App\Account');
	}
}
