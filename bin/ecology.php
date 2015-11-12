<?php

global $title, $description, $is_private, $fields;
$title = "Ecologia";
$description = "Lista com as características da ecologia por espécie.";
$is_private = false;
//$fields = ['family','scientificName','lifeForm','fenology','luminosity','substratum','longevity','resprout'];
// Field translation
$fields = ['familia','nome aceito','habito','fenologia','luminosidade','substrato','longevidade','rebroto'];
include 'base.php';

fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->ecology) && is_object($d->ecology)) {
      foreach($d->ecology as $k=>$v) {
        if(is_array($v)) {
          $d->ecology->$k = implode(' ; ',$v);
        }
      }
      $data =[
        $d->taxon->family
        , $d->taxon->scientificNameWithoutAuthorship
        , $d->ecology->lifeForm
        , $d->ecology->fenology
        , $d->ecology->luminosity
        , $d->ecology->substratum
        , $d->ecology->longevity
        , $d->ecology->resprout
        ];
      fputcsv($csv,$data);
    }
  }
}

