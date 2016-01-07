<?php

global $title, $description, $is_private, $fields;
$title = "Utilização das espécies";
$description = "Lista contendo as utilizações dadas a cada espécie do recorte.";
$is_private = false;
//$fields=['family','scientificName','use','resource','details','references'];
// Field translation
$fields=['família','nome científico','uso','recurso','detalhes','referências'];
include 'base.php';

fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->uses) && is_array($d->uses)) {
      foreach($d->uses as $t) {
        if(isset($t->use)) {
          $data=[
             $d->taxon->family
            ,$d->taxon->scientificNameWithoutAuthorship
            ,$t->use
            ,$t->resource
            ,$t->details
            ,implode(";",$t->references)
           ] ;
          fputcsv($csv,$data);
        }
      }
    }
  }
}

