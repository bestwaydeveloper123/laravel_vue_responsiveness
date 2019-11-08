<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    //builder macro search
    Builder::macro('search', function ($attributes, string $searchTerms) {
      $this->where(function (Builder $query) use ($attributes, $searchTerms) {
        foreach (array_wrap($attributes) as $attribute) {
          $symbols = ['"', "'", ",", "(", ")", "-", " "];
          $length = strlen($searchTerms);
          $splitTerms = [];
          $sbPos = 0;
          for ($i = 0; $i < $length; $i++) {
            if (in_array($searchTerms[$i], $symbols) || $i == $length - 1) {
              if ($i == $length - 1) {
                if (in_array($searchTerms[$i], $symbols)) {
                  $s = substr($searchTerms, $sbPos, $i - $sbPos);
                } else {
                  $s = substr($searchTerms, $sbPos);
                }
              } else {
                $s = substr($searchTerms, $sbPos, $i - $sbPos);
              }
              if ($s == FALSE || $s == '') {
                $sbPos = $i + 1;
                continue;
              }
              array_push($splitTerms, $s);
              $sbPos = $i + 1;
            }
          }

          $query->orWhere(function (Builder $query) use ($attribute, $splitTerms) {
            foreach ($splitTerms as $term) {
              $query->where($attribute, 'LIKE', "%{$term}%");
            }
          });
        }
      });
      return $this;
    });
  }
}
