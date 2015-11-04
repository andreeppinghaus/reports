<?php

global $title, $description, $is_private, $fields;
$title = "Ações";
$description = "Lista de ações de conservação necessárias ou em andamento.";
$is_private = true;
$fields = ["family","scientificName","action","situation",'details','references'];
include 'base.php';

fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->actions) && is_array($d->actions)) {
      foreach($d->actions as $t) {
          if(isset($t->action)) {
              if (!array_key_exists("situation", $t)){
                  $t->situation = "";
              }
              if (!array_key_exists("details", $t)){
                  $t->details = "";
              }
              if (!array_key_exists("references", $t)){
                  $t->references = array();
              }
              $data=[
                $d->taxon->family
                ,$d->taxon->scientificNameWithoutAuthorship
                ,$t->action
                ,$t->situation
                ,$t->details
                ,implode(";",$t->references)
              ];
                fputcsv($csv,$data);
          }
      }
    }
  }
}
