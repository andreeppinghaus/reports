<?php
require '../../vendor/autoload.php';
include_once 'ckan_connect.php';
include_once 'ckan_utils.php';

function publish($dataset_id, $gdrive_export, $name, $title, $base,
                 $description, $fields, $private_dataset)
{
    // Get CKAN client
    $client = get_ckan_client();

    // Check if dataset already exists
    $ckanResults = $client->package_search("cncflora_id:$dataset_id");
    $ckanResults = json_decode($ckanResults, true);

    //Delete existing datasets
    if ($ckanResults['result']['count'] > 0){
        foreach($ckanResults['result']['results'] as $item){
            $ckanResults = $client->package_delete($item['id']);
        }
        purge_datasets();
    }
    else {
        // Dataset could be private. Check if it is the case.
        $package_id = private_package_search($client, $dataset_id);
        //If it is, delete dataset. Otherwise, don't do anything. The dataset
        //will be created.
        if ($package_id) {
            $client->package_delete($package_id);
        }
        purge_datasets();
    }

    //Create resources array
    $resources = [];
    $formats = array('csv'=> "text/csv",
                     'pdf'=> "application/pdf",
                     'xlsx'=> "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    foreach($formats as $format=>$mimetype)
    {
        $resource_array = array(
            "url"=>$gdrive_export[$mimetype],
            "format"=>$format,
            "description"=>strtoupper($format)
        );
        $resources[] = $resource_array;
    }
    // Post new dataset
    $data = array("name"=> $name, "title"=> $title,
        "author"=>AUTHOR, "author_email"=>AUTHOR_EMAIL,
        "license_id"=>LICENSE_ID, "groups"=>array(array("id"=>GROUP_ID)),
        "tags"=>array(array("name"=>str_replace("_"," ",$base),
        "vocabulary_id"=>null)), "owner_org"=> ORG_ID,
        "extras"=>array(array("key"=>"cncflora_id", "value"=>$dataset_id),
        array("key"=>"Atributos oferecidos", "value"=>implode(", ", $fields))),
        "resources"=>$resources, "private"=>$private_dataset,
        "notes"=>$description
    );
    $ckanResults = $client->package_create(json_encode($data));
    $ckanResults = json_decode($ckanResults, true);
    return $ckanResults['result']['id'];
}
?>
