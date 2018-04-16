<?php

namespace App\Services\Opportunity;

use Illuminate\Support\Facades\DB;

class Filter
{
  var $queryBase;
  var $selectBase;
  var $whereBase;
  var $paginationBase;
  var $orderBase;

  public function __construct(Array $args) {
    $this->queryBase = Filter::get_query_base();
    $this->selectBase = Filter::get_select_base();

    $this->set_filters($args['filters']);
    $this->set_order($args['order']);
    $this->set_pagination($args['size'], $args['page']);
  }

  public function count() {
    return DB::select( $this->build_count_query() )[0]->count;
  }

  public function run($args = null) {
    $with_patination = isset($args['with_patination']) ?
      $args['with_patination'] : true;

    return DB::select( $this->build_select_query($with_patination) );
  }


  // Private


  private function set_filters($filters) {
    $where_keys = array(
      'opportunity_name' => 'opt.name',
      'account_name' => 'accounts.name',
    );

    $where_in_keys = array(
      'user_owner' => 'CONCAT(responsible_user.first_name, responsible_user.last_name)',
      'assigned' => 'CONCAT(assigned_user.first_name, assigned_user.last_name)',
      'development' => 'development.name',
      'sales_stage' => 'opt.sales_stage',
      'lead_source' => 'opt.lead_source',
      'close_motivation' => 'close_motivation.name',
    );

    if ( $filters->isEmpty() )
      return true;

    if ( $this->whereBase == null )
      $this->whereBase = ' WHERE';

    foreach($where_keys AS $key => $value)
      if ( isset($filters[$key]) )
        $this->whereBase =
        $this->add_like( $this->whereBase, $value, $filters[$key] );

    foreach($where_in_keys AS $key => $value)
      if ( isset($filters[$key]) )
        $this->whereBase =
        $this->add_whereIn( $this->whereBase, $value, $filters[$key] );
  }

  private function set_pagination($size = 15, $page = 1) {
    $size = !$size ? 15 : $size;

    $page = !$page ? 1: $page;

    $start = ($page * $size) - $size;

    $this->paginationBase = " LIMIT $start, $size";
  }

  private function set_order($order = null) {
    if( $order )
      $this->orderBase = " ORDER BY " . $order['name'] . " " . $order['order'];
  }


  private function build_select_query($with_patination = false) {
    if ( $with_patination )
      return $this->selectBase . $this->queryBase . $this->whereBase . $this->orderBase . $this->paginationBase;
    else
      return $this->selectBase . $this->queryBase . $this->whereBase . $this->orderBase;
  }

  private function build_count_query() {
    return 'SELECT COUNT(*) AS count' . $this->queryBase . $this->whereBase;
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
                   opt.sales_stage                     AS sales_stage,
                   close_motivation.name               AS close_motivation";
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
                           ON ( opportunity_user.user_id_c = responsible_user.id )
                   LEFT JOIN moc_motivation_of_closing_opportunities_c AS opportunity_close_motivation
                           ON ( opt.id = opportunity_close_motivation.moc_motivation_of_closing_opportunitiesopportunities_idb )
                   LEFT JOIN moc_motivation_of_closing AS close_motivation
                           ON ( close_motivation.id = opportunity_close_motivation.moc_motiva8482closing_ida )";
  }
}
