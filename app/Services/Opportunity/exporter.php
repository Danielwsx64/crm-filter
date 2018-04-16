<?php

namespace App\Services\Opportunity;

class Exporter
{
  var $opportunities;
  var $filename;
  var $fields = [
    'contact_name', 'contact_last_name', 'street', 'city', 'state', 'postalcode',
    'country', 'alt_street', 'alt_city', 'alt_state', 'alt_postalcode',
    'alt_country', 'phone', 'celphone', 'work', 'other', 'fax', 'email',
    'alt_email', 'other_email', 'opportunity', 'account', 'development',
    'user_name', 'user_last_name', 'assigned_name', 'assigned_last_name',
    'sales_stage', 'lead_source', 'close_motivation',
  ];

  public function __construct($opportunities, $filename = 'opportunities.csv') {
    $this->opportunities = $opportunities;
    $this->filename = $filename;
  }

  public function run() {
    $handle = fopen($this->filename, 'w');

    fputcsv($handle, $this->fields);

    foreach($this->opportunities as $opportunity)
      fputcsv($handle, $this->opportunity_as_array($opportunity));

    fclose($handle);

    return $this->filename;
  }


  // Private


  private function opportunity_as_array($opportunity) {
    $opportunity_array = Array();

    foreach($this->fields as $field)
      array_push($opportunity_array, $opportunity->$field);

    return $opportunity_array;
  }
}
