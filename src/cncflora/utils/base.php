<?php

if(php_sapi_name() !== 'cli' || !isset($argv) || count($argv) < 2 ) {
  echo "Invalid call.",PHP_EOL;
  exit;
}

$base = $argv[1];

preg_match('/([a-zA-Z0-9_]+)\.php$/',$argv[0],$reg);
$script = $reg[1];

echo "Start $script",PHP_EOL;

@mkdir(__DIR__.'/../data/'.$base);

$pwd = __DIR__;
chdir(__DIR__.'/../data/'.$base);

$file = __DIR__."/../data/".$base."/".str_replace("bin/","", str_replace(".php","",$script)).".csv";

if(
  ($base == 'livro_vermelho_2013' && file_exists($file)) 
  ||
  ($base == 'livro_vermelho_2013_revisao_2015')
) return;


include_once 'utils/config.php';
include 'utils/gdrive.php';
include 'utils/ckan.php';
echo "Using $base",PHP_EOL;

if(!file_exists("all.json") || (time() - filemtime("all.json")) > (10 * 60))  {
  // Always download database to get real time results
  // actually only if older tham 10 minutes
  echo "Downloading $base",PHP_EOL;
  passthru("curl 'http://cncflora.jbrj.gov.br/couchdb/".$base."/_all_docs?include_docs=true' -o '".__DIR__."/../data/".$base."/all.json'");
}

function retrim($obj){
  if(is_object($obj)) {
    foreach($obj as $k=>$v) {
      if(is_string($v)){
        $obj->$k = trim($v);
      } else if(is_object($v)) {
        $obj->$k = retrim($v);
      }
    }
  }
  return $obj;
}

$all = new StdClass;
$all->rows = array();
$af = fopen("all.json",'r');
fgets($af);
while($l = fgets($af)){
  $obj = json_decode(substr($l,0,-3));
  $obj2 = json_decode(substr($l,0,-2));//last obj
  if(is_object($obj)) {
    $all->rows[]=retrim($obj) ;
  } else if(is_object($obj2)) {
    $all->rows[]=retrim($obj2) ;
  }
}
#$all = json_decode(file_get_contents("all.json"));

$csv  = fopen($file,'w');
echo "Running $base",PHP_EOL;

register_shutdown_function(function() use ($pwd,$base,$script,$csv, $file,
    $title, $description, $fields, $is_private) {
        fclose($csv);
        // Check if CSV has more than just the header. Otherwise, doesn't add
        // it to CKAN.
        $rows =0;
        $save_doc = false;
        $csv = fopen($file, "r");
        if($csv){
            while(!feof($csv)){
                $content = fgets($csv);
                if($content)   $rows++;
                if($rows > 1){
                    $save_doc = true;
                    break;
                }
            }
        }
        fclose($csv);
        if ($save_doc) {
            $file_id = $script."_".$base;
            $folder_id = get_folder_id($base);
            $gdrive_export = update_gdrive($file_id, $title, $folder_id, $file);
            publish($file_id, $gdrive_export, $file_id,
                "$title do recorte ".ucwords(str_replace("_", " ", $base)), $base,
                $description, $fields, true);
            // Make all reports private for the moment
            //$is_private);
        }

        echo "Done $base",PHP_EOL;
    });
