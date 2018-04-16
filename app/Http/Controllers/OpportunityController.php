<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Opportunity;
use App\User;
use App\Development;

use App\Services\Opportunity\Filter;
use App\Services\Opportunity\Exporter;

class OpportunityController extends Controller
{
  public function __construct() {
    $this->middleware('auth');
  }

  public function index () {
    $developments = Development::select('name')->orderBy('name')->get();

    $users =
      User::select('first_name', 'last_name')
      ->where('deleted', '=', 0)
      ->orderBy('first_name')
      ->get();

    $sales_stages =
      Opportunity::select('sales_stage')
      ->groupBy('sales_stage')
      ->orderBy('sales_stage')
      ->get();

    $lead_sources =
      Opportunity::select('lead_source')
      ->groupBy('lead_source')
      ->orderBy('lead_source')
      ->get();

    return $this->render_view(
      [
        'developments' => $developments,
        'users' => $users,
        'sales_stages' => $sales_stages,
        'lead_sources' => $lead_sources 
      ]
    );
  }

  public function filter ( Request $request ) {
    $params = $request->all();

    $filter = new Filter($this->filter_options($params));

    $opportunities_count = $filter->count();
    $opportunities = $filter->run();

    $list_order = isset($params['list_order']) ?
      $params['list_order'] : array('name' => '', 'order' => 'ASC');

    $list_size = isset($params['list_size']) ? $params['list_size'] : 15;
    $list_page = isset($params['list_page']) ? $params['list_page'] : 1;

    $total_pages = $opportunities_count / $list_size;

    return $this->render_view(
      [
        'opportunities' => $opportunities,
        'total_pages' => $total_pages,
        'list_size' => $list_size,
        'list_page' => $list_page,
        'list_order' => $list_order
      ]
    );
  }

  public function export ( Request $request ) {
    $filter_options = $this->filter_options( $request->all() );

    $opportunities = (new Filter($filter_options))->run($filter_options);

    $filename = (new Exporter($opportunities, "opportunities.csv"))->run();

    return response()->download(
      $filename, $filename, array( 'Content-Type' => 'text/csv')
    );
  }


  // Private

  private function filter_options($params) {
    return Array(
      'filters' => $this->filter_params($params),
      'order' => isset($params['list_order']) ? $params['list_order'] : null,
      'size' => isset($params['list_size']) ? $params['list_size'] : null,
      'page' => isset($params['list_page']) ? $params['list_page'] : null,
      'with_patination' => isset($params['export']) ?
      $this->check_pagination($params['export']) : false
    );
  }

  private function check_pagination($arg) {
    return $arg == 'page' ? true : false;
  }

  private function filter_params($params) {
    $permited_filters = array(
      'opportunity_name', 'account_name', 'user_owner', 'assigned',
      'development', 'sales_stage', 'lead_source'
    );

    $params = collect($params);

    foreach($params AS $key => $value)
      if ( ! in_array($key, $permited_filters) )
        $params->pull($key);

    return $params;
  }
}
