<?php

if(!file_exists("all.json")) {
  file_put_contents("all.json",file_get_contents("http://cncflora.jbrj.gov.br/datahub/plantas_raras_cerrado/_all_docs?include_docs=true"));
}

$all = json_decode(file_get_contents("all.json"));

echo "\"Familia\";\"Especie\";\"Endemica\";\"Altitude\";\"EOO\";\"AOO\";\"Válidos\";\"Inválidos\";\"Não validados\";\"Total registros\"\n";

$cats = [];
foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='assessment') {
    if($d->metadata->status =='comments' || $d->metadata->status=='published'){
      if(isset($d->category) && strlen($d->category) == 2) {
        if(isset($d->evaluator) && strlen($d->evaluator) >= 1){
          $cats[$d->taxon->scientificNameWithoutAuthorship] = $d->category;
        }
      }
    }
  }
}

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($cats[$d->taxon->scientificNameWithoutAuthorship])) {
      echo $d->taxon->family.";".$d->taxon->scientificNameWithoutAuthorship.";";

      if(isset($d->distribution) && is_object($d->distribution)) {
        echo $d->distribution->brasilianEndemic.";";

        if(isset($d->distribution->altitude)) {
          $a = $d->distribution->altitude;
          if(isset($a->absolute)) {
            echo $a->absolute.";";
          } else if(isset($a->minimum) && isset($a->maximum)) {
            echo $a->minimum."~".$a->maximum.";";
          } else {
            echo $a->minimum.$a->maximum.";";
          }
        } else {
          echo ";";
        }
      } else {
        echo ";;";
      }

      $url = 'http://cncflora.jbrj.gov.br/plantas_raras_cerrado_occurrences/specie/'.urlencode($d->taxon->scientificNameWithoutAuthorship).'?json=true';
      shell_exec("curl '".$url."' -L -H 'Cookie: rack.session=485de10e24d76f25233101173b83b22347c8ac40e06e6cb1824c55e5cb8a9d06' > tmp_occs.json");
      $data = file_get_contents("tmp_occs.json");
      $json = json_decode($data);

      echo $json->stats->eoo.";";
      echo $json->stats->aoo.";";
      echo $json->stats->valid.";";
      echo $json->stats->invalid.";";
      echo $json->stats->not_validated.";";
      echo $json->stats->total.";";
      echo "\n";
    }
  }
}

