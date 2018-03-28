<?php namespace phoxy\route;

require_once('init.php');


$_PHOXY_ROUTE_CONFIG = load_config($_PHOXY_ROUTE_CONFIG_FILE);
if ($_PHOXY_ROUTE_CONFIG->quiet)
  $_PHOXY_HEADER_CONFIG_LINE = -1;
if ($_PHOXY_ROUTE_CONFIG->gzip)
  ini_set('zlib.output_compression_level', $_PHOXY_ROUTE_CONFIG->gzip);
handle($_PHOXY_ROUTE_CONFIG);
