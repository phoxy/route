<?php namespace phoxy\route;

require_once('init.php');

header_log("Startup");
$config = load_config($_PHOXY_ROUTE_CONFIG);

handle($config);
