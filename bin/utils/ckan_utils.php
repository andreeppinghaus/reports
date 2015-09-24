<?php
require '../../vendor/autoload.php';

function purge_datasets()
{
    $handle = fopen(CREDENTIALS_PATH."/cncflora_admin.txt", "r");
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
