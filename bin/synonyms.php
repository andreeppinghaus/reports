<?php

global $title, $is_private;
$title = "Sinônimos";
$is_private = false;
include 'base.php';

$fields = ["family","scientificNameWithoutAuthorship","scientificNameAuthorship","acceptedNameUsage"];
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


