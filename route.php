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
  $request = parse($_SERVER['REQUEST_URI']);
  $route = $request->url;

  header_log('initial data', $route);
  header_log('executing against', count($obj->rules), 'rules');

  foreach ($obj->rules as $k => $rule)
  {
    $regexp = $rule->regexp;

    if (empty($regexp))
    {
      internal\header_rule_log($k, "empty regexp");
      continue;
    }

    if ($regexp[0] != '/')
      $regexp = "/$regexp/";
    $res = preg_match("$regexp", $route, $matches);

    if ($res === false)
    {
      internal\header_rule_log($k, "regexp syntax error", $regexp);
      header_log('inconsistent configuration');
      echo "Issue in phoxy/route rule $k";
      die();
    }

    if (!$res)
    {
      internal\header_rule_log($k, "doesn't fit", $regexp);
      continue;
    }

    internal\header_rule_log($k, "matched", $regexp);

    if ($rule->static
      && ($rule->static !== true
        || $rule->static = "{$_SERVER['DOCUMENT_ROOT']}{$route}"))
      if (!file_exists($rule->static))
        $rule->not_found = true;
      else
      {
        $rule->die = true;

        $mtime = gmdate('D, d M Y H:i:s', filemtime($rule->static));

        if (@getallheaders()['If-Modified-Since'] == $mtime)
          $rule->http_code = "304 Not Modified";
        else
        {
          $mime = isset($rule->mime) ? $rule->mime : '';
          $max_age = isset($rule->max_age) ? $rule->max_age : 600;

          @header("Last-Modified: {$mtime}");
          @header("Cache-Control: public, max-age={$max_age}");
          @header("Content-Type: {$mime}");

          @header('Content-Length: ' . filesize($rule->static));
          readfile($rule->static);
        }
      }

    if ($rule->rewrite)
    {
      $route = preg_replace($regexp, $rule->rewrite, $route);
      internal\header_rule_log($k, "rewrite to", $route);
    }

    if ($location = $rule->found ? $rule->found : $rule->redirect)
    {
      $route = preg_replace($regexp, $location, $route);
      internal\header_rule_log($k, "redirect to", $route);

      header("Location: $route");
      $rule->http_code = $rule->found ? "307 Found" : "301 Redirect";
      $rule->die = true;
    }


    if ($rule->forbid)
    {
      $rule->http_code = $rule->echo = "403 Forbidden";
      $rule->die = true;
    }

    if ($rule->not_found)
    {
      $rule->http_code = $rule->echo = "404 Not Found";
      $rule->die = true;
    }

    if ($rule->http_code)
      header("HTTP/1.1 {$rule->http_code}");



    if ($rule->cat)
      echo file_get_contents($rule->cat);
    if ($rule->echo)
      echo $rule->echo;

    if ($rule->die)
      die();
  }

  header_log('no suitable action for url');
  header_log('quit');
}
