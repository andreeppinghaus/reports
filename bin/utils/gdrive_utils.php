<?php
require '../../vendor/autoload.php';
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
  }
  return str_replace('~', realpath($homeDirectory), $path);
}
function retrieveAllFiles($service, $parameters) {
  $result = array();
  $pageToken = NULL;

  do {
    try {
      if ($pageToken) {
        $parameters['pageToken'] = $pageToken;
      }
      $files = $service->files->listFiles($parameters);

      $result = array_merge($result, $files->getItems());
      $pageToken = $files->getNextPageToken();
    } catch (Exception $e) {
      print "An error occurred: " . $e->getMessage();
      $pageToken = NULL;
    }
  } while ($pageToken);
  return $result;
}

function create_folder($service, $base) {
    $file = new Google_Service_Drive_DriveFile();
    $file->setTitle(ucwords($base));
    $file->setMimeType( 'application/vnd.google-apps.folder' );
    $file = $service->files->insert( $file );

    $newProperty = new Google_Service_Drive_Property();
    $key = 'cncflora_id';
    $value = $base;
    $visibility = 'PUBLIC';
    $newProperty->setKey($key);
    $newProperty->setValue($value);
    $newProperty->setVisibility($visibility);
    try {
        $service->properties->insert($file->id, $newProperty);
    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }

    return $file->id;
}

function create_spreadsheet($service, $title, $file_id, $parent_id) {
    $file = new Google_Service_Drive_DriveFile();
    $file->setTitle($title);
    $file->setMimeType( 'application/vnd.google-apps.spreadsheet' );

    // To set parent folder
    $parent = new Google_Service_Drive_ParentReference();
    $parent->setId($parent_id);
    $file->setParents(array($parent));

    $file = $service->files->insert( $file );

    //Add property
    $newProperty = new Google_Service_Drive_Property();
    $key = 'cncflora_id';
    $value = $file_id;
    $visibility = 'PUBLIC';
    $newProperty->setKey($key);
    $newProperty->setValue($value);
    $newProperty->setVisibility($visibility);
    try {
        $service->properties->insert($file->id, $newProperty);
    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }


    return $file->id;
}

function delete_spreadsheet($service, $gdrive_id) {
    // Delete old spreadsheet
    try {
        $service->files->delete($gdrive_id);
    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }
}

function create_worksheet($client, $gdrive_id, $headers, $data) {
    $all_token = $client->getAccessToken();
    $access_token = json_decode($all_token);
    $serviceRequest = new DefaultServiceRequest($access_token->access_token, $access_token->token_type);
    ServiceRequestFactory::setInstance($serviceRequest);
    $spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
    $spreadsheetFeed = $spreadsheetService->getSpreadsheets();
    $spreadsheet = $spreadsheetFeed->getById($gdrive_id);
    $worksheetFeed = $spreadsheet->getWorksheets();
    $worksheet = $worksheetFeed[0];

    $cellFeed = $worksheet->getCellFeed();

    $cell_index = 1;
    foreach ($headers as $cell_header) {
        $cellFeed->editCell(1, $cell_index, $cell_header);
        $cell_index += 1;
    }

    $listFeed = $worksheet->getListFeed();
    foreach($data as $row) {
        $listFeed->insert($row);

    }
}
?>
