<?php

if(!file_exists("all.json")) {
  file_put_contents("all.json",file_get_contents("http://cncflora.jbrj.gov.br/datahub/plantas_raras_cerrado/_all_docs?include_docs=true"));
}

$all = json_decode(file_get_contents("all.json"));

echo "\"Familia\";\"Especie\";\"Fitofisionomia\"\n";

$cats = [];
foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='assessment') {
    if($d->metadata->status =='comments' || $d->metadata->status=='published'){
      if(isset($d->category) && strlen($d->category) == 2 && $d->category != 'DD') {
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
      if(isset($d->ecology) && isset($d->ecology->fitofisionomies) && is_array($d->ecology->fitofisionomies)) {
        foreach($d->ecology->fitofisionomies as $t) {
          echo $d->taxon->family.";".$d->taxon->scientificNameWithoutAuthorship.";";
          echo $t;
          echo "\n";
        }
      }
    }
  }
}

