<?php

if(php_sapi_name() !== 'cli' || !isset($argv) || count($argv) < 2 ) {
  echo "Invalid call.",PHP_EOL;
  exit;
}

$base = $argv[1];

if($base == 'livro_vermelho_2013') return;

preg_match('/([a-z]+)\.php$/',$argv[0],$reg);
$script = $reg[1];

echo "Start $script",PHP_EOL;

@mkdir(__DIR__.'/../data/'.$base);

$pwd = __DIR__;
chdir(__DIR__.'/../data/'.$base);

echo "Using $base",PHP_EOL;
if(!file_exists("all.json")) {
  echo "Downloading $base",PHP_EOL;
  passthru("curl 'http://cncflora.jbrj.gov.br/couchdb/".$base."/_all_docs?include_docs=true' -o '".__DIR__."/../data/".$base."/all.json'");
}

$all = json_decode(file_get_contents("all.json"));
var_dump($all->rows[0]);
$csv = fopen(__DIR__."/../data/".$base."/".str_replace("bin/","", str_replace(".php","",$script)).".csv",'w');
echo "Runing $base",PHP_EOL;

register_shutdown_function(function() use ($pwd,$base,$script,$csv) {
  fclose($csv);
  echo "Done $base",PHP_EOL;
});

