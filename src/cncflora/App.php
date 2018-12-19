<?php

namespace cncflora;

Config::config();

$r=new \Proton\Application;

$router = $r->GetRouter();

$r->get("/",function($req){
  header('Location: index.html');
  exit;
});

$r->get("/done",function($req,$res){
  $html = '<ul>';
  $dir = opendir(__DIR__.'/../../html/data');
 
  while($file = readdir($dir)) {
    if(!preg_match('/\.csv$/',$file)) continue;
    $html .= '<li><a href="data/'.$file.'">'.$file.'</a></li>';
  }

  $html .= "</ul>";

  $res->ok=true;
  $res->setContent($html);
  return $res;
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

$r->get('/generate/{report}/{extensao}',function($req,$res,$args){
  $name = $args['report'].'_'.date('Y-m-d H:m').'.'.$args['extensao'];
  $file = __DIR__."/../../html/data/".$name;
  $url  = 'data/'.$name;
  $res->setContent($url);
  return $res;
});

$r->get('/generate/{report}/{checklist}/{extensao}',function($req,$res,$args){
  $name = $args['checklist']."_".$args['report'].'_'.date('Y-m-d-Hm').'.'.$args['extensao'];
  $file = __DIR__."/../../html/data/".$name;
  $url  = 'data/'.$name;

  $class ='\\cncflora\\reports\\'.$args['report'];
  $report = new $class;
  $r = new \StdClass;

  $csv=fopen($file.".partial",'w');
  try{
    $report->run($csv,$args['checklist']);
    $r->ok=true;
    $r->file = $file;
    $r->url = $url;
  } catch(\Exception $e) {
    $r->ok=false;
    $r->error = $e->getMessage();
  }
  fclose($csv);

  rename($file.".partial",$file);

  $res->setContent(json_encode($r));
  return $res;
});

$r->get('/generate/{report}/{checklist}/{family}/{extensao}',function($req,$res,$args){
  if($args['family'] == "csv" || $args['family'] == "xls"){
    $date_occ = $args['extensao'];
    $args['extensao'] = $args['family'];
    $name = $args['checklist']."_".$args['report'].'_'.date('Y-m-d-Hm').'.'.$args['extensao'];
    $flag_modified = true;
  }
  else{
    $name = $args['checklist']."_".$args['family']."_".$args['report'].'_'.date('Y-m-d-Hm').'.'.$args['extensao'];
    $flag_modified = false;
  }

  $file = __DIR__."/../../html/data/".$name;
  $url  = 'data/'.$name;

  $class ='\\cncflora\\reports\\'.$args['report'];
  $report = new $class;
  $r = new \StdClass;

  $csv=fopen($file.".partial",'w');
  try {
    if($flag_modified)
      $report->run($csv,$args['checklist'],null,$date_occ);
    else
      $report->run($csv,$args['checklist'],$args['family']);
    $r->ok=true;
    $r->file = $file;
    $r->url = $url;
  } catch(\Exception $e) {
    $r->ok=false;
    $r->error = $e->getMessage();
  }
  fclose($csv);
  rename($file.".partial",$file);

  $res->setContent(json_encode($r));
  return $res;
});

$r->get('/generate/{report}/{checklist}/{family}/{species}/{extensao}',function($req,$res,$args){
  if($args['species'] == "csv" || $args['species'] == "xls"){
    $date_occ = $args['extensao'];
    $args['extensao'] = $args['species'];
    $name = $args['checklist']."_".$args['report']."_".$args['family'].'_'.date('Y-m-d-Hm').'.'.$args['extensao'];
    $flag_modified = true;
  }
  else{
    $name = $args['checklist']."_".$args['family']."_".str_replace(" ","_",trim( urldecode( $args['species'] ) ))."_".$args['report'].'_'.date('Y-m-d-Hm').'.'.$args['extensao'];
    $flag_modified=false;
  }

  $file = __DIR__."/../../html/data/".$name;
  $url  = 'data/'.$name;

  $class ='\\cncflora\\reports\\'.$args['report'];
  $report = new $class;
  $r = new \StdClass;

  $csv=fopen($file.".partial",'w');
  try {
    if($flag_modified)
      $report->run($csv,$args['checklist'],$args['family'],$date_occ);
    else
      $report->run($csv,$args['checklist'],$args['family'],urldecode( $args['species']) );
    $r->ok=true;
    $r->file = $file;
    $r->url = $url;
  } catch(\Exception $e) {
    $r->ok=false;
    $r->error = $e->getMessage();
  }
  fclose($csv);
  rename($file.".partial",$file);

  $res->setContent(json_encode($r));
  return $res;
});

$r->get('/book',function($req,$res) {
  $repo = new \cncflora\repository\Checklist;
  $dbs = $repo->getChecklists();
  ob_start();
  include __DIR__.'/../../html/book.php';
  $c = ob_get_contents();
  ob_end_clean();
  $res->setContent($c);
  return $res;
});

$r->get('/book/{db}',function($req,$res,$args) {
  $db = $args['db'];
  $repo = new \cncflora\repository\Taxon($db);
  $families = $repo->listFamilies();
  ob_start();
  include __DIR__.'/../../html/book_families.php';
  $c = ob_get_contents();
  ob_end_clean();
  $res->setContent($c);
  return $res;
});

$r->get('/book/{db}/TODAS',function($req,$res,$args) {
  $db = $args['db'];
  $taxonRepo = new \cncflora\repository\Taxon($db);
  $families = $taxonRepo->listFamilies();
  $assessments2 = [];
  $references2 = [];
  foreach($families as $f) {
    $refs=[];
    $repo=new \cncflora\repository\Assessment($db);
    $asses=$repo->listFamily($f);

    $repo2=new \cncflora\repository\Profiles($db);
    $profs=$repo2->listFamily($f);

    $assessments=[];
    foreach($asses as $a) {
      if(!($a['metadata']['status'] == 'published' || $a['metadata']['status'] == 'comments')) continue;

      foreach($profs as $p) {
        if($p['taxon']['scientificNameWithoutAuthorship'] == $a['taxon']['scientificNameWithoutAuthorship']) {
          $a['profile'] = $p;
        }
      }

      $assessments[] = $a;
      if(isset($a['references']) && is_array($a['references']))  {
        $refs = array_merge($refs,$a['references']);
      }

      if(isset($a['profile']['references']) && is_array($a['references'])) {
        $refs = array_merge($refs,$a['profile']['references']);
      }

    }
    $references=[];
    $got=[];
    foreach($refs as $r) {
      $letters =preg_replace('/[^a-zA-Z0-9]/','',$r);
      if(array_Search($letters,$got) === false) {
        $references[] = $r;
        $got[]=$letters;
      }
    }
    sort($references);

    usort($assessments,function($a,$b){
      return strcmp($a['taxon']['scientificNameWithoutAuthorship'],$b['taxon']['scientificNameWithoutAuthorship']);
    });
    $assessments2[$f] = $assessments;
    $references2[$f] = $references;
  }

  //tentando entender o porque de com log funcionar, e sem log retornar 502 Bad Gateway
  error_log(print_r($references2, TRUE));

  ob_start();
  include __DIR__.'/../../html/book_all.php';
  $c = ob_get_contents();
  ob_end_clean();
  $res->setContent($c);
  return $res;
});

$r->get('/book/{db}/{family}',function($req,$res,$args) {
  $db = $args['db'];
  $family = $args['family'];
  
  $refs=[];
  $repo=new \cncflora\repository\Assessment($db);
  $asses=$repo->listFamily($family);

  $repo2=new \cncflora\repository\Profiles($db);
  $profs=$repo2->listFamily($family);

  $assessments=[];
  foreach($asses as $a) {
    if(!($a['metadata']['status'] == 'published' || $a['metadata']['status'] == 'comments')) continue;

    foreach($profs as $p) {
      if($p['taxon']['scientificNameWithoutAuthorship'] == $a['taxon']['scientificNameWithoutAuthorship']) {
        $a['profile'] = $p;
      }
    }

    $assessments[] = $a;
    if(isset($a['references']) && is_array($a['references']))  {
      $refs = array_merge($refs,$a['references']);
    }
  }
  $references=[];
  $got=[];
  foreach($refs as $r) {
    $letters =preg_replace('/[^a-zA-Z0-9]/','',$r);
    if(array_Search($letters,$got) === false) {
      $references[] = $r;
      $got[]=$letters;
    }
  }
  sort($references);

  usort($assessments,function($a,$b){
    return strcmp($a['taxon']['scientificNameWithoutAuthorship'],$b['taxon']['scientificNameWithoutAuthorship']);
  });

  if (isset($_GET["inicio"]) ) {
      $inicio=$_GET["inicio"];
  }
  if (isset($_GET["fim"]) ) {
      $fim=$_GET["fim"];
  }
  
  if (isset($_GET["notreferences"]) ) {
      $desligareferencia=1;
  }else {
      $desligareferencia=0;
  }
  if (isset($_GET["yesreferences"]) ) {
      $somentereferencia=1;
  }else {
      $somentereferencia=0;
  }
  ob_start();
  if(isset($_GET["simple"])) {
    include __DIR__.'/../../html/book_family_simple.php';
  } elseif (isset($_GET["profile"])) {
    include __DIR__.'/../../html/book_family_profile.php';
  } elseif (isset($_GET["profilepdf"])) {
      include __DIR__.'/../../html/book_family_profile_pdf.php';
  } else {
    include __DIR__.'/../../html/book_family.php';
  }
  $c = ob_get_contents();
  ob_end_clean();
  $res->setContent($c);
  return $res;
});


$r->run();
