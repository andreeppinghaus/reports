<?php

include 'base.php';

$fields = ["family","scientificNameWithoutAuthorship","scientificNameAuthorship"];
fputcsv($csv,$fields);
$data_gdrive = array();

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
