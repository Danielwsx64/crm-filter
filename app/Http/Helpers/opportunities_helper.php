<?php

function get_opportunity_url($id) {
  return 'http://crm.atmosfera.com/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DOpportunities%26action%3DDetailView%26record%3D' . $id;
}

function format_info($info) {
  return ($info == '' || $info == '(00)0000-0000' ) ? '-' : $info;
}
