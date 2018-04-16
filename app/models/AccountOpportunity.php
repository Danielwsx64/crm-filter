<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountOpportunity extends Model
{
  protected $table = 'accounts_opportunities';

  public function account() {
    return $this->belongsTo('App\Account');
  }

}
