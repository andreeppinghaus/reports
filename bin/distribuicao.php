<?php

include 'base.php';

return;
echo "\"Familia\",\"Especie\",\"Endemica\",\"Altitude\",\"EOO\",\"AOO\",\"Válidos\",\"Inválidos\",\"Não validados\",\"Total registros\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
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

    /* got to use dwc */
    /*
    $url = 'http://cncflora.jbrj.gov.br/occurrences/'.$base.'/specie/'.urlencode($d->taxon->scientificNameWithoutAuthorship).'?json=true';
    shell_exec("curl '".$url."' -L -H 'Cookie: rack.session=485de10e24d76f25233101173b83b22347c8ac40e06e6cb1824c55e5cb8a9d06' > tmp_occs.json");
    $data = file_get_contents("tmp_occs.json");
    $json = json_decode($data);
    */

    echo $json->stats->eoo.";";
    echo $json->stats->aoo.";";
    echo $json->stats->valid.";";
    echo $json->stats->invalid.";";
    echo $json->stats->not_validated.";";
    echo $json->stats->total.";";
    echo "\n";
  }
}

