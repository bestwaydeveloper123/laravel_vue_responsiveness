<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AccountManagerPoint extends Model
{
  protected $guarded = ['id'];

  public function userApp()
  {
    return $this->hasOne('App\User', 'username', 'user_name');
  }

  public static function getManagerPoints($from, $to)
  {
    $points = [];
    if ($from == null && $to == null) {
      $from = Carbon::now()->subDays(8);
      $to = Carbon::now()->addDay('1');
      // $points = self::whereDate('created_at', Carbon::today());
      $points = self::whereBetween('created_at', [$from, $to]);
    } elseif ($to == null) {
      $points = self::whereDate('created_at', '>=', $from);
    } elseif ($from == null) {
      $points = self::whereDate('created_at', $to);
    } else {
      $points = self::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
    }
    return $points;
  }
}
