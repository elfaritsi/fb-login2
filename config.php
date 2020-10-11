<?php

require_once 'vendor/autoload.php';

if (!session_id())
{
    session_start();
}

// Call Facebook API

$facebook = new \Facebook\Facebook([
  'app_id'      => '368476250865220',
  'app_secret'     => '6d9bf39bfab0666d86c6047f24d28579',
  'default_graph_version'  => 'v2.10'
]);

?>