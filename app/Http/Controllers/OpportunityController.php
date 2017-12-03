<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

// use App\Account;
// use App\AccountOpportunity;
use App\Opportunity;
use App\User;
use App\Development;

class OpportunityController extends Controller
{
  var $queryBase;
  var $selectBase;
  var $whereBase;
  var $paginationBase;
  var $orderBase;

  public function __construct() {
    // $this->middleware('auth');
    $this->middleware('auth')->except('filter');
    $this->queryBase = OpportunityController::get_query_base();
    $this->selectBase = OpportunityController::get_select_base();
  }


  public function index () {

    $developments = Development::select('name')->orderBy('name')->get();
    $users = User::select('first_name', 'last_name')->where('deleted', '=', 0)->orderBy('first_name')->get();
    $sales_stages = Opportunity::select('sales_stage')->groupBy('sales_stage')->orderBy('sales_stage')->get();
    $lead_sources = Opportunity::select('lead_source')->groupBy('lead_source')->orderBy('lead_source')->get();

    return view('opportunity.index',
      [ 'developments' => $developments, 'users' => $users,
      'sales_stages' => $sales_stages, 'lead_sources' => $lead_sources ]);
  }


  public function filter ( Request $request ) {
    $params = $request->all();

    $this->add_filters_on_query($params);

    $total_opportunities = $this->get_query_count();
    $opportunities = $this->get_filtered_opportunities();

    $list_order = (isset($params['list_order'])) ? $params['list_order'] : array('name' => '', 'order' => 'ASC');
    $list_size = (isset($params['list_size'])) ? $params['list_size'] : 15;
    $list_page = (isset($params['list_page'])) ? $params['list_page'] : 1;
    $total_pages = $total_opportunities / $list_size;

    return view('opportunity._filter_result ',
      [ 'opportunities' => $opportunities,
        'total_pages' => $total_pages,
        'list_size' => $list_size,
        'list_page' => $list_page,
        'list_order' => $list_order
    ]);
  }

  public function export ( Request $request ) {
    $params = $request->all();

    unset($params['export']);

    $this->add_filters_on_query($params);

    if ( $request->input('export') == 'all' )
      $opportunities = DB::select( $this->build_query('all') );
    else
      $opportunities = DB::select( $this->build_query('page') );

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



  private function get_filtered_opportunities($params = []) {
    return DB::select( $this->build_query() );
  }

  private function get_query_count($params = []) {
    return DB::select( $this->build_query('count') )[0]->count;
  }

  private function build_query($type = 'page') {
    if ( $type == 'all' )
      return $this->selectBase . $this->queryBase . $this->whereBase . $this->orderBase;
    elseif ( $type == 'page' )
      return $this->selectBase . $this->queryBase . $this->whereBase . $this->orderBase . $this->paginationBase;
    else
      return 'SELECT COUNT(*) AS count' . $this->queryBase . $this->whereBase;
  }

  private function add_filters_on_query($params) {
    $expected_wheres = array(
      'opportunity_name' => 'opt.name',
      'account_name' => 'accounts.name',
    );

    $expected_where_ins = array(
      'user_owner' => "CONCAT(responsible_user.first_name, responsible_user.last_name)",
      'assigned' => "CONCAT(assigned_user.first_name, assigned_user.last_name)",
      'development' => 'development.name',
      'sales_stage' => 'opt.sales_stage',
      'lead_source' => 'opt.lead_source',
    );

    $params = collect($params);

    $params->pull('_token');

    $this->set_order( $params->pull('list_order') );

    $this->set_pagination( $params->pull('list_size'), $params->pull('list_page') );

    if ( $params->isEmpty() )
      return true;

    if ( $this->whereBase == null )
      $this->whereBase = ' WHERE';

    foreach($expected_wheres AS $key => $value)
      if ( isset($params[$key]) )
        $this->whereBase =
        $this->add_like( $this->whereBase, $value, $params[$key] );

    foreach($expected_where_ins AS $key => $value)
      if ( isset($params[$key]) )
        $this->whereBase =
        $this->add_whereIn( $this->whereBase, $value, $params[$key] );
  }


  private function add_like($query, $column, $value) {

    return ( preg_match('/WHERE$/', $query) ) ?
            $query . " $column LIKE '%$value%'" :
            $query . " AND $column LIKE '%$value%'";
  }

  private function add_whereIn($query, $column, $value) {
    $in_list = '';

    for($i=0; $i < sizeof($value); $i++)
      $in_list = $in_list . "'$value[$i]', ";

    $in_list = rtrim($in_list, ", ");

    return ( preg_match('/WHERE$/', $query) ) ?
            $query . " $column IN ($in_list)" :
            $query . " AND $column IN ($in_list)";
  }

  private function set_order($order = null) {
    if( $order )
      $this->orderBase = " ORDER BY " . $order['name'] . " " . $order['order'];
  }

  private function set_pagination($list_size = 15, $list_page = 1) {
    if( !$list_size )
      $list_size = 15;

    if( !$list_page )
      $list_page = 1;

    $start = ($list_page * $list_size) - $list_size;

    $this->paginationBase = " LIMIT $start, $list_size";
  }

  private function get_select_base() {
    return "SELECT contacts.first_name                 AS contact_name,
                   contacts.last_name                  AS contact_last_name,
                   contacts.primary_address_street     AS street,
                   contacts.primary_address_city       AS city,
                   contacts.primary_address_state      AS state,
                   contacts.primary_address_postalcode AS postalcode,
                   contacts.primary_address_country    AS country,
                   contacts.alt_address_street         AS alt_street,
                   contacts.alt_address_city           AS alt_city,
                   contacts.alt_address_state          AS alt_state,
                   contacts.alt_address_postalcode     AS alt_postalcode,
                   contacts.alt_address_country        AS alt_country,
                   contacts.phone_home                 AS phone,
                   contacts.phone_mobile               AS celphone,
                   contacts.phone_work                 AS work,
                   contacts.phone_other                AS other,
                   contacts.phone_fax                  AS fax,
                   (SELECT e.email_address
                    FROM   email_addresses AS e
                           INNER JOIN email_addr_bean_rel AS erel
                                   ON ( e.id = erel.email_address_id
                                        AND erel.deleted = 0 )
                    WHERE  erel.bean_module = 'Contacts'
                           AND erel.bean_id = contacts.id
                    LIMIT  1)                          AS email,
                   (SELECT e.email_address
                    FROM   email_addresses AS e
                           INNER JOIN email_addr_bean_rel AS erel
                                   ON ( e.id = erel.email_address_id
                                        AND erel.deleted = 0 )
                    WHERE  erel.bean_module = 'Contacts'
                           AND erel.bean_id = contacts.id
                    LIMIT  2, 1)                       AS alt_email,
                   (SELECT e.email_address
                    FROM   email_addresses AS e
                           INNER JOIN email_addr_bean_rel AS erel
                                   ON ( e.id = erel.email_address_id
                                        AND erel.deleted = 0 )
                    WHERE  erel.bean_module = 'Contacts'
                           AND erel.bean_id = contacts.id
                    LIMIT  3, 1)                       AS other_email,
                   opt.name                            AS opportunity,
                   opt.id                              AS opportunity_id,
                   accounts.name                       AS account,
                   development.name                    AS development,
                   responsible_user.first_name         AS user_name,
                   responsible_user.last_name          AS user_last_name,
                   assigned_user.first_name            AS assigned_name,
                   assigned_user.last_name             AS assigned_last_name,
                   opt.lead_source                     AS lead_source,
                   opt.sales_stage                     AS sales_stage";
  }

  private function get_query_base() {
    return " FROM   contacts AS contacts
                   INNER JOIN opportunities_contacts
                           ON ( contacts.id = opportunities_contacts.contact_id
                                AND opportunities_contacts.deleted = 0
                                AND contacts.deleted = 0 )
                   INNER JOIN opportunities AS opt
                           ON ( opt.id = opportunities_contacts.opportunity_id
                                AND opt.deleted = 0 )
                   INNER JOIN accounts_opportunities AS accounts_opportunities
                           ON ( opt.id = accounts_opportunities.opportunity_id
                                AND accounts_opportunities.deleted = 0 )
                   INNER JOIN accounts
                           ON ( accounts_opportunities.account_id = accounts.id
                                AND accounts.deleted = 0 )
                   INNER JOIN users AS assigned_user
                           ON ( opt.assigned_user_id = assigned_user.id
                                AND assigned_user.deleted = 0 )
                   INNER JOIN emp_empreendimentos_opportunities_c AS optemp
                           ON (
                   opt.id = optemp.emp_empreendimentos_opportunitiesopportunities_idb
                   AND optemp.deleted = 0 )
                   INNER JOIN emp_empreendimentos AS development
                           ON (
                   development.id = optemp.emp_empreendimentos_opportunitiesemp_empreendimentos_ida
                   AND development.deleted = 0 )
                   INNER JOIN opportunities_cstm AS opportunity_user
                           ON ( opt.id = opportunity_user.id_c )
                   INNER JOIN users AS responsible_user
                           ON ( opportunity_user.user_id_c = responsible_user.id )";
  }
}
