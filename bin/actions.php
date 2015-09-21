<?php

include 'base.php';
include 'utils/gdrive.php';

$fields = ["family","scientificName","action","situation",'details','references'];
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
                //All keys have to be the header name but lowercase
              $row_gdrive = array(
                  "family" => $d->taxon->family,
                  "scientificname" => $d->taxon->scientificNameWithoutAuthorship,
                  "action" => $t->action,
                  "situation" => $t->situation,
                  "details" => $t->details,
                  "references" => implode(";", $t->references));
                fputcsv($csv,$data);
              $data_gdrive[] = $row_gdrive;
          }
      }
    }
  }
}

$file_id = "actions_$base";
$folder_id = get_folder_id($base);
update_gdrive($file_id, "Actions", $fields, $data_gdrive, $folder_id);
