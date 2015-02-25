<?php

$dbs = json_decode(file_get_contents("http://cncflora.jbrj.gov.br/couchdb/_all_dbs"));
foreach($dbs as $db) {
  if(!preg_match('/^_/',$db) && !preg_match('/_history$/',$db)){
    $dir = opendir(__DIR__);
    while(($f = readdir($dir)) !== false) {
      if($f != "all.php" && $f != 'base.php' && preg_match("/.php$/",$f)) {
        passthru("php ".__DIR__."/$f $db".PHP_EOL);
      }
    }
  }
}


