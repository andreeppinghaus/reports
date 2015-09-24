<?php
require '../../vendor/autoload.php';
include_once 'ckan_connect.php';
include_once 'ckan_utils.php';

function publish($dataset_id, $gdrive_export, $name, $title, $base)
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
    $data = array("name"=> $name, "title"=> ucwords($title),
        "author"=>AUTHOR, "author_email"=>AUTHOR_EMAIL,
        "license_id"=>LICENSE_ID, "groups"=>array(array("id"=>GROUP_ID)),
        "tags"=>array(array("name"=>str_replace("_"," ",$base),
        "vocabulary_id"=>null)), "owner_org"=> ORG_ID,
        "extras"=>array(array("key"=>"cncflora_id", "value"=>$dataset_id)),
        "resources"=>$resources
    );
    $client->package_create(json_encode($data));
}
?>
