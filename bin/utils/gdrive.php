<?php
require '../../vendor/autoload.php';
include_once 'gdrive_connect.php';
include_once 'gdrive_utils.php';


function get_folder_id($base) {
    // Get the API client and construct the service object.
    $client = getClient();
    $service = new Google_Service_Drive($client);

    if ($client->getAccessToken()) {
        $parameters = array('q' => "properties has { key='cncflora_id' and value='$base' and visibility='PUBLIC' }");
        $result = retrieveAllFiles($service,$parameters);

        if (count($result) >= 1) {
            // If too many results, return the first one.
            $gdrive_id = $result[0]->id;
            // If file is in trash, untrash it
            $service->files->untrash($gdrive_id);
        }
        elseif (count($result) == 0) {
            $gdrive_id = create_folder($service, $base);
        }
    }
    return $gdrive_id;
}

function update_gdrive($file_id, $title, $folder_id, $filename) {
    // Get the API client and construct the service object.
    $client = getClient();
    $service = new Google_Service_Drive($client);

    if ($client->getAccessToken()) {
        $parameters = array('q' => "properties has { key='cncflora_id' and value='$file_id' and visibility='PUBLIC' }");
        $result = retrieveAllFiles($service,$parameters);

        if (count($result) == 1){
            // Everything ok, just one file with this id
            $gdrive_id = $result[0]->id;
            delete_spreadsheet($service, $gdrive_id);
        }
        elseif (count($result) > 1) {
            // Too many results. Delete all.
            foreach ($result as $spreadsheet) {
                $gdrive_id = $spreadsheet->id;
                delete_spreadsheet($service, $gdrive_id);
            }
        }
        $gdrive_id = create_spreadsheet($client, $service, $title, $file_id,
                                        $filename, $folder_id);
        //create_worksheet($client, $gdrive_id, $headers, $data);
    }
}
?>
