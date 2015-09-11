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
  print "Files:\n";
  foreach ($results->getItems() as $file) {
    printf("%s (%s)\n", $file->getTitle(), $file->getId());
  }
/************************************************
  If we're signed in then lets try to upload our
  file.
 ************************************************/
if ($client->getAccessToken()) {
$file = new Google_Service_Drive_DriveFile();
$file->setTitle( 'Hello world!' );
$file->setMimeType( 'application/vnd.google-apps.spreadsheet' );
//$file = $service->files->insert( $file );

$all_token = $client->getAccessToken();
$access_token = json_decode($all_token);
$serviceRequest = new DefaultServiceRequest($access_token->access_token, $access_token->token_type);
ServiceRequestFactory::setInstance($serviceRequest);
$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();

foreach ($spreadsheetFeed as $entry) {
    print_r($entry->getTitle());
}
}
}

?>
