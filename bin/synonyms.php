<?php

include 'base.php';

$fields = ["family","scientificNameWithoutAuthorship","scientificNameAuthorship","acceptedNameUsage"];
fputcsv($csv,$fields);

foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='taxon') {
        if($doc->taxonomicStatus == 'accepted') {
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


