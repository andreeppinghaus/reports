<?php

$dbs = json_decode(file_get_contents("http://cncflora.jbrj.gov.br/couchdb/_all_dbs"));
foreach($dbs as $db) {
  if(!preg_match('/^_/',$db) && !preg_match('/_history$/',$db) && $db != "public") {
    $dir = opendir(__DIR__);
    @unlink(__DIR__."/../data/".$db."/all.json");
    while(($f = readdir($dir)) !== false) {
      if($f != "." && $f != '..' 
        && $f != "all.php" 
        && $f != "base.php" 
        && preg_match("/\.php$/",$f) 
        && !preg_match("/^\./",$f)) {
        passthru("php ".__DIR__."/$f $db".PHP_EOL);
      }
    }
  }
}

$dbs = opendir(__DIR__."/../data");
while(($db = readdir($dbs)) !== false) {
  if($db != "." && $db != '..' ) {
    echo "->".$db."\n";
    $db_dir = opendir(__DIR__."/../data/".$db);

    $sql   = '';
    if($db_dir) {
      while(($csv_file = readdir($db_dir)) !== false) {
        if(!preg_match("/\.csv$/",$csv_file)) continue;
        echo "-->".$csv_file."\n";
        $csv  = fopen(__DIR__."/../data/".$db."/".$csv_file,'r');
        if($csv) {
          $head = fgetcsv($csv,0,',','"');
          $fields = [];
          if(is_array($head) && count($head) >= 1) {
            foreach($head as $field) {
              $fields[] = $field." VARCHAR(5000)";
            }

            $table = str_replace(".csv","",$csv_file);
            $sql .= "CREATE TABLE IF NOT EXISTS ".$table." (".implode(" , ",$fields).");\n";

            while(($row = fgetcsv($csv,0,',','"')) !== false) {
              $sql .= "INSERT INTO ".$table." VALUES ('".implode("','",$row)."');\n";
            }
          }

          fclose($csv);
        }
      }
      closedir($db_dir);
    }

    file_put_contents(__DIR__."/../data/".$db."/data.sql",$sql);
    @unlink(__DIR__."/../data/".$db."/data.db");
    $sqlite = new PDO('sqlite:'.__DIR__.'/../data/'.$db."/data.db");
    $sqlite->exec('PRAGMA synchronous = OFF');
    $sqlite->exec('PRAGMA journal_mode = MEMORY');
    $sqlite->exec($sql);
  }
}
closedir($dbs);

passthru("python bin/csv2xlsx");



