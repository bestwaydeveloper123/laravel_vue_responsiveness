<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ProgrammingEntry extends Model
{
  protected $guarded = ['id'];

  public function account()
  {
    return $this->belongsTo('App\Account');
  }

  public static function getEntriesBetweemDate($from, $to)
  {
    $entries = [];
    if ($from == null && $to == null) {
      $entries = self::whereDate('created_at', Carbon::today());
    } elseif ($to == null) {
      $entries = self::where('created_at', '>=', $from);
    } elseif ($from == null) {
      $entries = self::whereDate('created_at', $to);
    } else {
      $entries = self::whereBetween('created_at', [$from, $to]);
    }
    return $entries;
  }
}
