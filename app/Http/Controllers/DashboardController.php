<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\User;
use App\Opportunity;

class DashboardController extends Controller
{

  public function __construct() {
    $this->middleware('auth');
  }

  public function index() {

    $opportunities = $this->get_opportunities_betteewn();

    $info =  $this->get_opportunities_info(null, true, $opportunities);
    $chart = $this->get_opportunities_chart($opportunities);

    return view('dashboard.index',
      [ 'opportunities' => $info, 'chart' => $chart ] );
  }


  // Private function

  private function get_opportunities_chart($_opportunities = null) {

    $date = Carbon::now();

    if ( $_opportunities != null )
      $opportunities = $_opportunities;
    else
      $opportunities = $this->get_opportunities_betteewn();

    for ($i = 0; $i < 12; $i++ )
      $chart_info[$i] = $this->get_opportunities_info($date, false, $opportunities);

    return $chart_info;
  }

  private function get_opportunities_betteewn($array = null) {

    if ($array == null) {
      $date = Carbon::now();
      $end = $date->toDateString();
      $start = $date->subYear()->toDateString();
    }else{
      $start = $array[0];
      $end = $array[1];
    }

    return Opportunity::select('date_entered', 'sales_stage')->whereBetween(
      'date_entered', array( $start, $end  ) )->orderBy('date_entered', 'asc')->get();
  }

  private function get_opportunities_info(Carbon $_date = null, $percent = true, $_opportunities = null) {

    $date = $_date != null ? $_date : $date = Carbon::now();

    $opt_array['month'] = $date->format('F Y');
    $opt_array['end_date'] = $date->toDateString();
    $opt_array['start_date'] = $date->subMonth()->toDateString();

    if ( $_opportunities != null )
      $opportunities = $_opportunities;
    else
      $opportunities = $this->get_opportunities_betteewn(
        Array($opt_array['start_date'], $opt_array['end_date'])
      );

    $opt_array['news'] = $opportunities->where(
      'date_entered', '>=', $opt_array['start_date'] )->where(
        'date_entered', '<=', $opt_array['end_date']
      )->count();

    $opt_array['opened'] = $opportunities->where(
      'date_entered', '>=', $opt_array['start_date'] )->where(
        'date_entered', '<=', $opt_array['end_date']
      )->whereNotIn('sales_stage', [ 'Closed Lost', 'Closed Won'  ] )->count();

    $opt_array['won'] = $opportunities->where(
      'date_entered', '>=', $opt_array['start_date'])->where(
        'date_entered', '<=', $opt_array['end_date']
      )->where('sales_stage', '=', 'Closed Won' )->count();

    $opt_array['lost'] = $opportunities->where(
      'date_entered', '>=', $opt_array['start_date'])->where(
        'date_entered', '<=', $opt_array['end_date']
      )->where('sales_stage', '=', 'Closed Lost' )->count();

    if($percent) {
      $finished_op = $opt_array['opened'] + $opt_array['won'] + $opt_array['lost'];

      $opt_array['opened_percent'] = $finished_op == 0 ? 0 : ($opt_array['opened']*100)/$finished_op;
      $opt_array['won_percent'] = $finished_op == 0 ? 0 : ($opt_array['won']*100)/$finished_op;
      $opt_array['lost_percent'] = $finished_op == 0 ? 0 : ($opt_array['lost']*100)/$finished_op;
    }

    return $opt_array;
  }
}
