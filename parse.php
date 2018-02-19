<?php

namespace phoxy\route\internal;

function recursive_urn_decode($arr)
{
  $ret = [];
  foreach ($arr as $k => $v)
    if (is_array($v))
      $ret[$k] = recursive_urn_decode($v);
    else
      $ret[$k] = urldecode($v);

  return $ret;
}

function parse_uri($uri)
{
  @list($path, $querystring) = explode("?", $uri, 2);
  parse_str($querystring, $urlvars);

  return recursive_urn_decode([$path, $urlvars]);
}

namespace phoxy\route;

function parse($url_string)
{
  $obj = internal\parse_uri($url_string);

  $ret =
  [
    'url' => $obj[0],
    'get' => $obj[1],
  ];

  return new \phpa2o\phpa2o($ret);
}
