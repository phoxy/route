<?php namespace phoxy\route;

require_once('init.php');

$config = load_config($_PHOXY_ROUTE_CONFIG);
if ($config->quiet)
  $_HEADER_CONFIG_LINE = -1;
if ($config->gzip)
  ini_set('zlib.output_compression_level', $config->gzip);
handle($config);
