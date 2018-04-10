<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $incrementing = false;
    public $keyType = 'string';


    /**
     ** Overrides the method to ignore the remember token.
     **/
    public function setAttribute($key, $value) {
      $isRememberTokenAttribute = $key == 'remember_token';

      if (!$isRememberTokenAttribute)
        parent::setAttribute($key, $value);

    }
}
