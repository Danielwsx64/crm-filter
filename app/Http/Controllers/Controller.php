<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function render_view($args){
      $trace = debug_backtrace(2,2);

      $action = $trace[1]['function'];
      $controller_name = explode('\\', $trace[1]['class'])[3];
      $controller_name = strtolower($controller_name);
      $controller_name = str_replace('controller', '', $controller_name);

      return view("$controller_name.$action", $args);
    }
}
