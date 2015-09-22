<?php
require '../../vendor/autoload.php';
include_once 'ckan_connect.php';
//include_once 'ckan_utils.php';

function get_dataset_id()
{
    // Get CKAN client
    $client = get_ckan_client();

    // Post new dataset
    $data = (object)[
        "name"=> "Name-String",
        "title"=> "String",
        "url"=> urlencode("http://localhost/dataset/especies2"),
        "notes"=> "String",
        "tags"=> ["teste", "testando"],
        "maintainer"=> "String",
        "maintainer_email"=> "String",
        "organization"=> "c4e9e8d4-0ec4-4a92-99f2-ac0898a88cf6"
    ];
try{
    $ckanResults = $client->package_create($data);
    $ckanResults = json_decode($ckanResults, true);
    print_r($ckanResults);
}
catch(Exception $e)
{
     echo $e->getMessage()."\n";
}
//$dataset = $client->get_package_entity("08e9602a-fedb-4e32-a101-83bb31c71285");
    //print_r($dataset);
}

get_dataset_id();
?>
