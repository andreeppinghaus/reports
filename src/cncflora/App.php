<?php
namespace cncflora;

Config::config();

$r=new \Proton\Application;

$router = $r->GetRouter();

$r->get("/",function($req){
  header('Location: index.html');
  exit;
});

$r->get("/reports",function($req,$res){
  $reports=[];

  $dir = opendir(__DIR__.'/reports');
  while($file = readdir($dir)) {
    if(!preg_match('/[A-Z][a-z_]+\.php/',$file)) continue;

    $class_name = str_replace(".php","",$file);
    $content = file_get_contents(__DIR__."/reports/".$file);
    if(strpos($content,'class '.$class_name) === false) continue;

    $class= "\\cncflora\\reports\\$class_name";
    $report = new $class;
    $report->name=$class_name;
    $reports[]=$report;
  }

  sort($reports);
  $res->setContent(json_encode($reports));
  return $res;
});

$r->get("/checklists",function($req,$res){
  $repo = new \cncflora\repository\Checklist;
  $checklists = $repo->getChecklists();

  $res->setContent(json_encode($checklists));
  return $res;
});

$r->get('/checklist/{checklist}/families',function($req,$res,$args){
    $db = $args['checklist'];
    $repo = new \cncflora\repository\Taxon($db);
    $families = $repo->listFamilies();
    $res->setContent(json_encode($families));
    return $res;
});

$r->get('/checklist/{checklist}/family/{family}/species',function($req,$res,$args){
    $db = $args['checklist'];
    $family = $args['family'];
    $repo = new \cncflora\repository\Taxon($db);
    $species = $repo->listFamily($family);
    $res->setContent(json_encode($species));
    return $res;
});

$r->get('/generate/{report}',function($req,$res,$args){
  $name = $args['report'].'_'.date('Y-m-d H:m').".csv";
  $file = __DIR__."/../../html/data/".$name;
  $url  = 'data/'.$name;
  $res->setContent($url);
  return $res;
});

$r->get('/generate/{report}/{checklist}',function($req,$res,$args){
  $name = $args['checklist']."_".$args['report'].'_'.date('Y-m-d-Hm').".csv";
  $file = __DIR__."/../../html/data/".$name;
  $url  = 'data/'.$name;

  $class ='\\cncflora\\reports\\'.$args['report'];
  $report = new $class;

  $csv=fopen($file,'w');
  $report->run($csv,$args['checklist']);
  fclose($csv);

  $res->setContent($url);
  return $res;
});

$r->get('/generate/{report}/{checklist}/{family}',function($req,$res,$args){
  $name = $args['checklist']."_".$args['family']."_".$args['report'].'_'.date('Y-m-d-Hm').".csv";
  $file = __DIR__."/../../html/data/".$name;
  $url  = 'data/'.$name;

  $class ='\\cncflora\\reports\\'.$args['report'];
  $report = new $class;

  $csv=fopen($file,'w');
  $report->run($csv,$args['checklist'],$args['family']);
  fclose($csv);

  $res->setContent($url);
  return $res;
});

$r->get('/generate/{report}/{checklist}/{family}/{species}',function($req,$res,$args){
  $name = $args['checklist']."_".$args['family']."_".str_replace(" ","_",trim( urldecode( $args['species'] ) ))."_".$args['report'].'_'.date('Y-m-d-Hm').".csv";
  $file = __DIR__."/../../html/data/".$name;
  $url  = 'data/'.$name;
  $res->setContent($url);
  return $res;
});

$r->run();
