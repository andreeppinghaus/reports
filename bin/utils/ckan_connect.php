<?php
require '../../vendor/autoload.php';
include_once 'config.php';
use CKAN\CkanClient;

function get_ckan_client() {
    // Connect to CKAN and return client
    $handle = fopen(CREDENTIALS_PATH."/cncflora.txt", "r");
    $token = fgets($handle);
    $token = str_replace(array("\n", "\t", "\r"), '', $token);
    $ckan = new CkanClient(CKAN_URL.CKAN_API, $token);
    return $ckan;
}
?>
