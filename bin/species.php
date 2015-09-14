<?php

include 'base.php';
include 'utils/gdrive.php';

//$fields = ["family","scientificNameWithoutAuthorship","scientificNameAuthorship"];
$fields = ["family", "name","nameAuthorship"];
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
            $row_gdrive = array("family" => $doc->family,
                                //"scientificNameWithoutAuthorship" => $doc->scientificNameWithoutAuthorship,
                                "name" => $doc->scientificNameWithoutAuthorship,
                                //"scientificNameAuthorship" => $doc->scientificNameAuthorship);
                                "nameAuthorship" => $doc->scientificNameAuthorship);
            fputcsv($csv,$data);
            $data_gdrive[] = $row_gdrive;
        }
    }
}

$file_id = "species_$base";
update_gdrive($file_id, "Species", $fields, $data_gdrive);
?>
