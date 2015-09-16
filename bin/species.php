<?php

include 'base.php';
include 'utils/gdrive.php';

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
            //All keys have to be the header name but lowercase
            $row_gdrive = array("family" => $doc->family,
                                "scientificnamewithoutauthorship" => $doc->scientificNameWithoutAuthorship,
                                "scientificnameauthorship" => $doc->scientificNameAuthorship);
            fputcsv($csv,$data);
            $data_gdrive[] = $row_gdrive;
        }
    }
}

$file_id = "species_$base";
$folder_id = get_folder_id($base);
update_gdrive($file_id, "Species", $fields, $data_gdrive, $folder_id);
?>
