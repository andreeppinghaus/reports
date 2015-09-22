<?php
require '../../vendor/autoload.php';
define('CKAN_URL', 'http://localhost:5000/api/3/');
$HOME = getenv("HOME");
define('CREDENTIALS_PATH', "$HOME/.credentials/cncflora.txt");
use CKAN\CkanClient;

function get_ckan_client() {
    // Connect to CKAN and return client
    $handle = fopen(CREDENTIALS_PATH, "r");
    $token = fgets($handle);
    $ckan = new CkanClient(CKAN_URL, $token);
    return $ckan;
}
?>
