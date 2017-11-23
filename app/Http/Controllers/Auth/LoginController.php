<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\User;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/home';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->middleware('guest')->except('logout');
  }

  // Fake Login
  public function login(Request $request) {

    $user_name = $request->input('name');
    $password = $request->input('password');

    $user = User::where('user_name', $user_name)->first();

    $password_md5 = md5($password);
    $user_hash = $user->user_hash;


    // Old way to validate SugarCRM pass - just md5 password
    if( $user_hash[0] != '$' && strlen($user_hash) == 32 )
      $valid = strtolower($password_md5) == $user_hash;
    else
      $valid = crypt(strtolower($password_md5), $user_hash) == $user_hash;

    if( ! $valid )
      return view('auth.login', [ 'name' => $user_name, 'error' => 'Usuário ou senha inválidos' ]);

    \Illuminate\Support\Facades\Auth::login($user);
    return redirect()->intended('defaultpage');

  }
}
