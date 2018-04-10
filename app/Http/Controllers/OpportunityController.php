<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Opportunity;
use App\User;
use App\Development;

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

    $filter = new \App\Services\Opportunity\Filter(
      Array(
        'filters' => $this->filter_params($params),
        'order' => isset($params['list_order']) ? $params['list_order'] : null,
        'size' => isset($params['list_size']) ? $params['list_size'] : null,
        'page' => isset($params['list_page']) ? $params['list_page'] : null
      )
    );

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
    $params = $request->all();

    $filter = new \App\Services\Opportunity\Filter(
      Array(
        'filters' => $this->filter_params($params),
        'order' => isset($params['list_order']) ? $params['list_order'] : null,
        'size' => isset($params['list_size']) ? $params['list_size'] : null,
        'page' => isset($params['list_page']) ? $params['list_page'] : null
      )
    );

    if ( $request->input('export') == 'all' )
      $opportunities = $filter->run(['with_patination' => false]);
    else
      $opportunities = $filter->run();

    $filename = "opportunities.csv";
    $handle = fopen($filename, 'w');

    fputcsv($handle, array(
      'contact_name',
      'contact_last_name',
      'street',
      'city',
      'state',
      'postalcode',
      'country',
      'alt_street',
      'alt_city',
      'alt_state',
      'alt_postalcode',
      'alt_country',
      'phone',
      'celphone',
      'work',
      'other',
      'fax',
      'email',
      'alt_email',
      'other_email',
      'opportunity',
      'account',
      'development',
      'user_name',
      'user_last_name',
      'assigned_name',
      'assigned_last_name',
      'sales_stage',
      'lead_source',
    ));

    foreach($opportunities as $opportunity) {
      fputcsv($handle, array(
        $opportunity->contact_name,
        $opportunity->contact_last_name,
        $opportunity->street,
        $opportunity->city,
        $opportunity->state,
        $opportunity->postalcode,
        $opportunity->country,
        $opportunity->alt_street,
        $opportunity->alt_city,
        $opportunity->alt_state,
        $opportunity->alt_postalcode,
        $opportunity->alt_country,
        $opportunity->phone,
        $opportunity->celphone,
        $opportunity->work,
        $opportunity->other,
        $opportunity->fax,
        $opportunity->email,
        $opportunity->alt_email,
        $opportunity->other_email,
        $opportunity->opportunity,
        $opportunity->account,
        $opportunity->development,
        $opportunity->user_name,
        $opportunity->user_last_name,
        $opportunity->assigned_name,
        $opportunity->assigned_last_name,
        $opportunity->sales_stage,
        $opportunity->lead_source,

      ));
    }

    fclose($handle);


    $headers = array( 'Content-Type' => 'text/csv');

    return response()->download($filename, $filename, $headers);
  }


  // Private


  private function filter_params($params) {
    $permited_filters = array(
      'opportunity_name',
      'account_name',
      'user_owner',
      'assigned',
      'development',
      'sales_stage',
      'lead_source'
    );

    $params = collect($params);

    foreach($params AS $key => $value)
      if ( ! in_array($key, $permited_filters) )
        $params->pull($key);

    return $params;
  }
}
