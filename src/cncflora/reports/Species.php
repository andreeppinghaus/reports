<?php

global $title, $description, $is_private, $fields;
$title = "Espécies";
$description = "Lista com todas as espécies do recorte.";
$is_private = false;
//$fields = ["family","scientificNameWithoutAuthorship","scientificNameAuthorship"];
// Field translation
$fields = ["família","nome científico","autor"];
include 'base.php';

fputcsv($csv,$fields);

$got=[];
foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='taxon') {
        if($doc->taxonomicStatus == 'accepted') {
            if(isset($got[strtolower( $doc->scientificNameWithoutAuthorship)] )) continue;
              $got[strtoloweR($doc->scientificNameWithoutAuthorship)]=true;
            $data=[
               strtoupper($doc->family)
              ,$doc->scientificNameWithoutAuthorship
              ,$doc->scientificNameAuthorship
          ];
            fputcsv($csv,$data);
        }
    }
}

?>
