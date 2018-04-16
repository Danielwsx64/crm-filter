<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
  public function account_opportunity() {
    return $this->hasOne('App\AccountOpportunity');
  }
}
