<?PHP
require_once('./vendor/autoload.php');
require_once('./config/config.php');

use SocalNick\Orchestrate\Client;
$client = new Client($config_orchestrate_apikey,'https://api.ctl-uc1-a.orchestrate.io/v0/');

if(!$config_orchestrate_apikey) {
    die('Missing/Invalid Orchestrate.io API Key. If you\'re a user seeing this message, please contact the website administrator');
}

$validationRequest = curl_init($config_orchestrate_listings_url);
curl_setopt($validationRequest, CURLOPT_USERPWD, $config_orchestrate_apikey);
curl_setopt($validationRequest, CURLOPT_TIMEOUT, 30);
curl_setopt($validationRequest, CURLOPT_RETURNTRANSFER, TRUE);
$return = curl_exec($validationRequest);

switch(curl_getinfo($validationRequest, CURLINFO_HTTP_CODE)) {
    case 200:
        break;
    case 401:
        curl_close($validationRequest);
        die('Invalid API Key used for Orchestrate.io');
    default:
        curl_close($validationRequest);
        die('Unrecognized HTTP code received in validating Orchestrate.io API Key: ' . curl_getinfo($validationRequest, CURLINFO_HTTP_CODE));
}

curl_close($validationRequest);