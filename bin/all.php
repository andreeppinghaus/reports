<?php

$dbs = json_decode(file_get_contents("http://cncflora.jbrj.gov.br/couchdb/_all_dbs"));
foreach($dbs as $db) {
  if(!preg_match('/^_/',$db) && !preg_match('/_history$/',$db)){
    $dir = opendir(__DIR__);
    @unlink(__DIR__."/../data/".$db."/all.json");
    while(($f = readdir($dir)) !== false) {
      if($f != "." && $f != '..' && $f != "all.php" && $f != 'base.php' && preg_match("/\.php$/",$f) && !preg_match("/^\./",$f)) {
        passthru("php ".__DIR__."/$f $db".PHP_EOL);
      }
    }
  }
}


