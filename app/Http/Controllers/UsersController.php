<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use App\User;

class UsersController extends Controller
{

  public function __construct() {
    $this->middleware('auth');
  }

  public function index() {

    $user = User::where('user_name', 'daniel')->first();
    // Auth::login($user);
    // Auth::logout();



    // return $user->user_name;
    return view('users.index', [ 'user' => $user, 'senha' =>  Hash::make('guernica') ] );
  }
}
