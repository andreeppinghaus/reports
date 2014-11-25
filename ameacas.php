<?php

if(!file_exists("all.json")) {
  file_put_contents("all.json",file_get_contents("http://cncflora.jbrj.gov.br/datahub/plantas_raras_cerrado/_all_docs?include_docs=true"));
}

$all = json_decode(file_get_contents("all.json"));

echo "\"Familia\";\"Especie\";\"Ameaça\";\"Incidencia\";\"Periodo\";\"Declínio\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->threats) && is_array($d->threats)) {
      foreach($d->threats as $t) {
        if(isset($t->threat)) {
          echo $d->taxon->family.";".$d->taxon->scientificNameWithoutAuthorship.";";
          echo $t->threat.";";
          echo $t->incidence.";";
          echo implode(",",$t->timing).";";
          echo implode(",",$t->decline).";";
          echo "\n";
        }
      }
    }
  }
}

