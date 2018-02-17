<?php namespace phoxy\route;

function load_config($file_list = [])
{
  $status =
  [
    'load_result' => true,
    'failed' => [],
  ];

  $result = [];

  foreach ($file_list as $config_file)
  {
    if
    (
      !is_file($config_file)
      || !($content = @file_get_contents($config_file))
      || false === ($obj = yaml_parse($content))
    )
    {
      $status['load_result'] = false;
      $status['failed'][] = $config_file;
      continue;
    }

    $result = array_replace_reqursive($result, $obj);
  }

  $result = array_replace($result, $status);

  return $result;
}
