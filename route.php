<?php namespace phoxy\route;

function route($obj)
{
  $route = parse($_SERVER['REQUEST_URI']);

  header_log('initial data', $route->url);
  header_log('executing against', count($obj->rules), 'rules');
  foreach ($obj->rules as $rule)
  {

  }

  header_log('no suitable action for url');
  header_log('quit');
}
