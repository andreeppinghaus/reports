<?php

if(!file_exists("all.json")) {
  file_put_contents("all.json",file_get_contents("http://cncflora.jbrj.gov.br/datahub/plantas_raras_cerrado/_all_docs?include_docs=true"));
}

$all = json_decode(file_get_contents("all.json"));

echo "\"Familia\";\"Especie\";\"Ação\";\"Situação\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->actions) && is_array($d->actions)) {
      foreach($d->actions as $t) {
        if(isset($t->action)) {
          echo $d->taxon->family.";".$d->taxon->scientificNameWithoutAuthorship.";";
          echo $t->action.";";
          echo $t->situation.";";
          echo "\n";
        }
      }
    }
  }
}

