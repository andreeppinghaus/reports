<?php
require '../../vendor/autoload.php';
//use Google\Spreadsheet\DefaultServiceRequest;
//use Google\Spreadsheet\ServiceRequestFactory;

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
            $service->properties->insert($file->getId(), $newProperty);
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }

        $permission = new Google_Service_Drive_Permission();
        //$permission->setValue('cncflora.net');
        //$permission->setType('domain');
        $permission->setValue('');
        $permission->setType('anyone');
        $permission->setRole('reader');

        $service->permissions->insert(
            $file->getId(), $permission);

        return $file->getId();
    }

    function create_spreadsheet($client, $service, $title, $file_id, $filename,
                                $parent_id) {
        $file = new Google_Service_Drive_DriveFile();
        $file->setTitle($title);
        $client->setDefer(true);

        // To set parent folder
        $parent = new Google_Service_Drive_ParentReference();
        $parent->setId($parent_id);
        $file->setParents(array($parent));

        $request = $service->files->insert( $file, array("convert"=> true));

        $chunkSizeBytes = 1 * 1024 * 1024;
        // Create a media file upload to represent our upload process.
        $media = new Google_Http_MediaFileUpload(
            $client,
            $request,
            'text/csv',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($filename));

        // Upload the various chunks. $status will be false until the process is
        // complete.
        $status = false;
        $handle = fopen($filename, "rb");
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }

        // The final value of $status will be the data from the API for the object
        // that has been uploaded.
        $result = false;
        if ($status != false) {
            $result = $status;
        }

        fclose($handle);
        // Reset to the client to execute requests immediately in the future.
        $client->setDefer(false);

        //Add property
        $response = insertProperty($service, $result->getId(), 'cncflora_id', $file_id, 'PUBLIC');

        //Make it public
        $permission = new Google_Service_Drive_Permission();
        //$permission->setValue('cncflora.net');
        //$permission->setType('domain');
        $permission->setValue('');
        $permission->setType('anyone');
        $permission->setRole('reader');

        $service->permissions->insert(
            $result->getId(), $permission);
        return ($result->exportLinks);
}

function insertProperty($service, $fileId, $key, $value, $visibility) {
  $newProperty = new Google_Service_Drive_Property();
  $newProperty->setKey($key);
  $newProperty->setValue($value);
  $newProperty->setVisibility($visibility);
  try {
    return $service->properties->insert($fileId, $newProperty);
  } catch (Exception $e) {
    print "An error occurred: " . $e->getMessage();
  }
  return NULL;
}

function delete_spreadsheet($service, $gdrive_id) {
    // Delete old spreadsheet
    try {
        $service->files->delete($gdrive_id);
    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }
}

//function create_worksheet($client, $gdrive_id, $headers, $data) {
    //$all_token = $client->getAccessToken();
    //$access_token = json_decode($all_token);
    //$serviceRequest = new DefaultServiceRequest($access_token->access_token, $access_token->token_type);
    //ServiceRequestFactory::setInstance($serviceRequest);
    //$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
    //$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
    //$spreadsheet = $spreadsheetFeed->getById($gdrive_id);
    //$worksheetFeed = $spreadsheet->getWorksheets();
    //$worksheet = $worksheetFeed[0];

    //$cellFeed = $worksheet->getCellFeed();

    //$cell_index = 1;
    //foreach ($headers as $cell_header) {
        //$cellFeed->editCell(1, $cell_index, $cell_header);
        //$cell_index += 1;
    //}

    //$listFeed = $worksheet->getListFeed();
    //foreach($data as $row) {
        //$listFeed->insert($row);

    //}
//}
?>
