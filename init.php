<?php namespace phoxy\route;

$_HEADER_CONFIG_LINE = 0;
function header_log($data)
{
  global $_HEADER_CONFIG_LINE;

  $args =
  [
    'PHOXY-ROUTE-LOG-'
    , sprintf('%03d', $_HEADER_CONFIG_LINE++)
    , ':'
    , $data
  ];

  header(implode('', $args));
}


$default_configurations =
[
  __DIR__ . '/route.yaml',
  $_SERVER['DOCUMENT_ROOT'] . '/route.yaml',
];

if (!isset($_PHOXY_ROUTE_CONFIG))
  $_PHOXY_ROUTE_CONFIG = $default_configurations;

require_once('load_config.php');
require_once('handle.php');
