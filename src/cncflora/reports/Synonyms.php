<?php

global $title, $description, $is_private, $fields;
$title = "Sinônimos";
$description = "Lista com os sinônimos de todas as espécies e os respectivos nomes aceitos.";
$is_private = false;
$fields = ["family","scientificNameWithoutAuthorship","scientificNameAuthorship","acceptedNameUsage"];
// Field translation
$fields = ["família","nome científico","autor","nome aceito"];
include 'base.php';

fputcsv($csv,$fields);

foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='taxon') {
        if($doc->taxonomicStatus == 'synonym') {
            $data=[
               $doc->family
              ,$doc->scientificNameWithoutAuthorship
              ,$doc->scientificNameAuthorship
              ,$doc->acceptedNameUsage
            ];
            fputcsv($csv,$data);
        }
    }
}


