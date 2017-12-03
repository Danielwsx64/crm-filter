<?php

function user_full_name($user, $limit = 0) {
  $full_name = $user->first_name . ' ' . $user->last_name;

  if($limit > 0)
    return str_limit($full_name, $limit, '...');

  return $full_name;
}
