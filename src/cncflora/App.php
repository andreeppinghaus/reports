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
    if(!preg_match('/^[A-Z][a-zA-Z_]+\.php$/',$file)) continue;

    $class_name = str_replace(".php","",$file);
    $content = file_get_contents(__DIR__."/reports/".$file);
    if(strpos($content,'class '.$class_name) === false) continue;

    $class= "\\cncflora\\reports\\$class_name";
    $report = new $class;
    $report->name=$class_name;
    $reports[]=$report;
  }

  usort($reports,function($a,$b) {
    return strcmp($a->title,$b->title);
  });
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
  $r = new \StdClass;

  $csv=fopen($file,'w');
  try {
    $report->run($csv,$args['checklist']);
    $r->ok=true;
    $r->file = $file;
    $r->url = $url;
  } catch(\Exception $e) {
    $r->ok=false;
    $r->error = $e->getMessage();
  }
  fclose($csv);

  $res->setContent(json_encode($r));
  return $res;
});

$r->get('/generate/{report}/{checklist}/{family}',function($req,$res,$args){
  $name = $args['checklist']."_".$args['family']."_".$args['report'].'_'.date('Y-m-d-Hm').".csv";
  $file = __DIR__."/../../html/data/".$name;
  $url  = 'data/'.$name;

  $class ='\\cncflora\\reports\\'.$args['report'];
  $report = new $class;
  $r = new \StdClass;

  $csv=fopen($file,'w');
  try {
    $report->run($csv,$args['checklist'],$args['family']);
    $r->ok=true;
    $r->file = $file;
    $r->url = $url;
  } catch(\Exception $e) {
    $r->ok=false;
    $r->error = $e->getMessage();
  }
  fclose($csv);

  $res->setContent(json_encode($r));
  return $res;
});

$r->get('/generate/{report}/{checklist}/{family}/{species}',function($req,$res,$args){
  $name = $args['checklist']."_".$args['family']."_".str_replace(" ","_",trim( urldecode( $args['species'] ) ))."_".$args['report'].'_'.date('Y-m-d-Hm').".csv";
  $file = __DIR__."/../../html/data/".$name;
  $url  = 'data/'.$name;

  $class ='\\cncflora\\reports\\'.$args['report'];
  $report = new $class;
  $r = new \StdClass;

  $csv=fopen($file,'w');
  try {
    $report->run($csv,$args['checklist'],$args['family'],urldecode( $args['species']) );
    $r->ok=true;
    $r->file = $file;
    $r->url = $url;
  } catch(\Exception $e) {
    $r->ok=false;
    $r->error = $e->getMessage();
  }
  fclose($csv);

  $res->setContent(json_encode($r));
  return $res;
});

$r->run();
