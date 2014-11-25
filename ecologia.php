<?php

if(!file_exists("all.json")) {
  file_put_contents("all.json",file_get_contents("http://cncflora.jbrj.gov.br/datahub/plantas_raras_cerrado/_all_docs?include_docs=true"));
}

$all = json_decode(file_get_contents("all.json"));

echo "\"Familia\";\"Especie\";\"Habito\";\"Fenologia\";\"Luminosidade\";\"Substrato\";\"Longevidade\";\"Rebroto\"\n";

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
      if(isset($d->ecology) && is_object($d->ecology)) {
        echo $d->taxon->family.";".$d->taxon->scientificNameWithoutAuthorship.";";
        echo $d->ecology->lifeForm.";";
        echo $d->ecology->fenology.";";
        echo $d->ecology->luminosity.";";
        echo $d->ecology->substratum.";";
        echo $d->ecology->longevity.";";
        echo $d->ecology->resprout.";";
        echo "\n";
      }
    }
  }
}

