<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentsImages extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'account_id',
        'image',
        'thumbnail',
        'description'
       
    ];


	public function account ()
	{
		return $this->belongsTo('App\Account');
	}
}
