<?php
require 'vendor/autoload.php';
use Google\Spreadsheet\Request;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

define('APPLICATION_NAME', 'Relatórios CNCFlora');
define('CREDENTIALS_PATH', '~/.credentials/cncflora.json');
define('CLIENT_SECRET_PATH', 'client_secret.json');
define('SCOPES', implode(' ', array(
    Google_Service_Drive::DRIVE,
    'https://spreadsheets.google.com/feeds',
    'https://docs.google.com/feeds')
));

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->setScopes(SCOPES);
  $client->setAuthConfigFile(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = file_get_contents($credentialsPath);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->authenticate($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, $accessToken);
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->refreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, $client->getAccessToken());
  }
  return $client;
}

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

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Drive($client);

// Print the names and IDs for up to 10 files.
$optParams = array(
  'maxResults' => 10,
);
$results = $service->files->listFiles($optParams);

if (count($results->getItems()) == 0) {
  print "No files found.\n";
} else {
  //print "Files:\n";
  //foreach ($results->getItems() as $file) {
    //printf("%s (%s)\n", $file->getTitle(), $file->getId());
  //}
/************************************************
  If we're signed in then lets try to upload our
  file.
 ************************************************/
if ($client->getAccessToken()) {
//$file = new Google_Service_Drive_DriveFile();
//$file->setTitle( 'Hello world!' );
//$file->setMimeType( 'application/vnd.google-apps.spreadsheet' );
//$file = $service->files->insert( $file );

//$newProperty = new Google_Service_Drive_Property();
//$key = 'cncflora_id';
//$value = '12345';
//$visibility = 'PUBLIC';
  //$newProperty->setKey($key);
  //$newProperty->setValue($value);
  //$newProperty->setVisibility($visibility);
  //try {
      //return $service->properties->insert($file->id, $newProperty);
  //} catch (Exception $e) {
    //print "An error occurred: " . $e->getMessage();
  //}
  $parameters = array('q' => "properties has { key='cncflora_id' and value='12345' and visibility='PUBLIC' }");
  $result = retrieveAllFiles($service,$parameters);
  //print_r($result);

$all_token = $client->getAccessToken();
$access_token = json_decode($all_token);
$serviceRequest = new DefaultServiceRequest($access_token->access_token, $access_token->token_type);
ServiceRequestFactory::setInstance($serviceRequest);
$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
$spreadsheet = $spreadsheetFeed->getById($result[0]->id);
$spreadsheet->addWorksheet('Temp worsheet', 50, 20);
$worksheetFeed = $spreadsheet->getWorksheets();
$worksheet = $worksheetFeed->getByTitle('Pagina1');
$worksheet->delete();
$spreadsheet->addWorksheet('Pagina1', 50, 20);
$worksheet = $worksheetFeed->getByTitle('Temp worsheet');
$worksheet->delete();
$worksheetFeed = $spreadsheet->getWorksheets();
$worksheet = $worksheetFeed->getByTitle('Pagina1');
$cellFeed = $worksheet->getCellFeed();

$cellFeed->editCell(1,1, "name");
$cellFeed->editCell(1,2, "age");

$listFeed = $worksheet->getListFeed();
$row = array('name'=>'John', 'age'=>26);
$listFeed->insert($row);
}
}

?>
