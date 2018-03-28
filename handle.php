<?php namespace phoxy\route;

function handle($obj)
{
  if ($obj['load_result'])
    header_log('config load succeed');
  else
  {
    header_log('config load issues withing files:');
    foreach ($obj['failed'] as $file)
      header_log($file);
  }

  route($obj);
}
