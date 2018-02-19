<?php

namespace phoxy\route\internal;

function header_rule_log($rule)
{
  $args = func_get_args();
  $rule = array_shift($args);

  call_user_func_array('\phoxy\route\header_log', array_merge(["RULE", $rule], $args));
}

namespace phoxy\route;

function route($obj)
{
  $route = parse($_SERVER['REQUEST_URI']);

  header_log('initial data', $route->url);
  header_log('executing against', count($obj->rules), 'rules');

  foreach ($obj->rules as $k => $rule)
  {
    $regexp = $rule->regexp;

    if (empty($regexp))
    {
      internal\header_rule_log($k, "empty regexp");
      continue;
    }

    $res = preg_match("/$regexp/", $request, $matches);
    if (!$res)
    {
      internal\header_rule_log($k, "doesn't fit");
      continue;
    }
  }

  header_log('no suitable action for url');
  header_log('quit');
}
