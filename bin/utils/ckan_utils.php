<?php
require '../../vendor/autoload.php';

function private_package_search($client, $package_id)
{
    $ckanResults = $client->organization_show("cncflora", true);
    $ckanResults = json_decode($ckanResults, true);
    foreach($ckanResults['result']['packages'] as $package) {
        $package_item = $client->package_show($package['id']);
        $package_item = json_decode($package_item, true);
        foreach($package_item['result']['extras'] as $metadata){
            if ($metadata['key'] == 'cncflora_id' && $metadata['value'] == $package_id){
                return $package_item['result']['id'];
            }
        }
    }
    return false;
}

function purge_datasets()
{
    $env = getenv("PHP_ENV");
    $handle = fopen(CREDENTIALS_PATH."/cncflora_admin_$env.txt", "r");
    $token = fgets($handle);
    $token = str_replace(array("\n", "\t", "\r"), '', $token);
    $url = CKAN_URL."/ckan-admin/trash";
    $data_string = "purge-packages=purge";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: $token"));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    curl_exec($curl);
}

?>
