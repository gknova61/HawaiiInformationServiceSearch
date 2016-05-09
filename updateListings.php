<?php
/**
 * Created by PhpStorm.
 * User: Keanu
 * Date: 5/7/2016
 * Time: 9:43 PM
 */

require_once("config/config.php");
require_once("config/dbInfo.php");
require_once("libs/phputils/php/phputils.class.php");
require_once('libs/chromephp/php/chromephp.php');

set_time_limit(0); //This is a long script, usually takes ~10min depending on database size of HIIS

function createSearchUrl($realOrBase,$pageNumber,$islandNumber,$district) {
    global $config_importio_hiis_GUID_search;
    global $config_importio_apikey ;

    if($realOrBase == 'real') {
        $Url = 'http://www.alohaliving.com/search/?page='. $pageNumber .'&ipp=100&island='. $islandNumber .'&District='. $district .'&minprice=0&maxprice=9999999999999&minbeds=0&minbaths=0';
    }else if($realOrBase == 'base') {
        $Url = 'https://extraction.import.io/query/extractor/'. $config_importio_hiis_GUID_search .'?_apikey='. $config_importio_apikey .'&url=http%3A%2F%2Fwww.alohaliving.com%2Fsearch%2F%3Fpage%3D'. $pageNumber .'%26ipp%3D100%26island%3D'. $islandNumber .'%26District%3D'. $district .'%26minprice%3D0%26maxprice%3D9999999999999%26minbeds%3D0%26minbaths%3D0';
    }else {
        return false;
    }

    return $Url;
}

function importIOQuery($url,$returnChecksum = false) {
    $pageData = file_get_contents($url);
    if($returnChecksum) {
        return array(json_decode($pageData),'checksum' => md5($pageData));
    }
    return json_decode($pageData);
}

function checkUrl($url,$searchOrListing,$addToDatabase = true) {
    global $con;
    global $config_importio_apikey;
    global $config_importio_hiis_GUID_search;
    global $config_importio_hiis_GUID_listing;

    if(!$con) {
        ChromePhp::warn('There is no MySQL Connection. Therefore, we cannot check to see if the listing data is in the database');
        return 'No SQL Connection';
    }

    $pageData = file_get_contents($url);

    //Stripping ad data that keeps changing on page reload. Everything from featuredlistor, to modal fade
    $pageDataTempArray = explode('featuredlistor',$pageData);
    $pageData = $pageDataTempArray[0] . substr($pageDataTempArray[count($pageDataTempArray)-1],strpos($pageDataTempArray[count($pageDataTempArray)-1],'modal fade'));

    $checksum = md5($pageData);

    $result = query("SELECT id , IF(realChecksum = '" . mysqli_real_escape_string($con,$checksum) . "' , realChecksum, '') AS realChecksum FROM real_estate_app.listings_extractor_log WHERE realChecksum = '" . mysqli_real_escape_string($con,$checksum) . "' LIMIT 1;");

    if($result) {
        return true;
    }else {
        if($searchOrListing == 'search') {
            if($addToDatabase) {
                $importUrl = 'https://extraction.import.io/query/extractor/'. $config_importio_hiis_GUID_search .'?_apikey='. $config_importio_apikey .'&url='. urlencode($url);
                $result = importIOQuery($importUrl,true);
                query("INSERT INTO `real_estate_app`.`listings_extractor_log` (`resourceId`, `url`, `searchTerms`, `checksum`, `data`, `realUrl`, `realChecksum`, `realData`) VALUES ('" . mysqli_real_escape_string($con,$result[0]->extractorData->resourceId) . "', '" . mysqli_real_escape_string($con,$importUrl) . "', '', '" . mysqli_real_escape_string($con,$result['checksum']) . "', '" . mysqli_real_escape_string($con,json_encode($result[0])) . "', '" . mysqli_real_escape_string($con,$url) . "', '" . mysqli_real_escape_string($con,$checksum) . "', '" . mysqli_real_escape_string($con,$pageData) . "');");
            }
            return false;
        }else if($searchOrListing == 'listing') { //TODO Make sure listings get handled correctly
            if($addToDatabase) {
                $importUrl = 'https://extraction.import.io/query/extractor/' . $config_importio_hiis_GUID_listing . '?_apikey=' . $config_importio_apikey . '&url=' . urlencode($url);
                $result = importIOQuery($importUrl,true);
                var_dump($result[0]);
                //query("INSERT INTO `real_estate_app`.`listings` (`resource_id`, `address`, `price`, `bedrooms`, `mls`, `price/sqft`, `interior_area_size`, `year_built`, `lot_size`, `land_tenure`, `on_market_since`, `last_updated`, `property_type`, `oceanfront`, `description`, `misc_data`, `dateFetched`, `statusCode`, `checksum`) VALUES ('ggggg', '556 st', '49584', '4', '494943', '493', '5858585', '2007', '9485acres', 'single family', 'June 26, 2011', 'June 27, 2014', 'land', '1', 'This is a cool place.', 'blaeh', 'June 27, 2016', '200', 'wdawdwawaa');")
            }
        }else {
            return false;
        }
    }
}

function fetchDirectUrl($url,$searchOrListing) {
    return;
}

function fetchCacheUrl($url,$searchOrListing) {
    global $con;

    $cached = checkUrl($url,$searchOrListing);

    if($searchOrListing == 'search') {
        $result = query("SELECT data , IF(realUrl = '" . mysqli_real_escape_string($con,$url) . "' , realUrl, '') AS realUrl FROM real_estate_app.listings_extractor_log WHERE realUrl = '" . mysqli_real_escape_string($con,$url) . "' LIMIT 1;");
        return json_decode($result[0]['data']);
    }else if($searchOrListing == 'listing') {
        $result = query("SELECT his_listing_id,url,resource_id,address,price,bedrooms,mls,price/sqft,interior_area_size,year_built,lot_size,land_tenure,on_market_since,last_updated,property_type,oceanfront,description,misc_data,dateFetched , IF(url = '" . mysqli_real_escape_string($con, $url) . "' , url, '') AS url FROM real_estate_app.listings_extractor_log WHERE url = '" . mysqli_real_escape_string($con, $url) . "' LIMIT 1;");
        return $result;
    }
}

//Connect to MySQL
$con=mysqli_connect($dbServer,$dbUsername,$dbPassword,$dbDatabase);
if (mysqli_connect_errno())
{
    die('Please contact support with this error. Failed to connect to MySQL: ' . mysqli_connect_error());
}

$realUrl = createSearchUrl('real',1,3,'',0,999999999999,0,0);
$first_page_data = fetchCacheUrl($realUrl,'search');
$total_pages = intval($first_page_data->extractorData->data[0]->group[0]->{'Amount of Pages'}[0]->text);

for($i = 1; $i <= $total_pages; $i++) {
    $page_number = $i;
    echo $i;
    $page_data = fetchCacheUrl(createSearchUrl('real',$i,3,'',0,999999999999,0,0),'search');

    for($p = 0; $p <= count($page_data->extractorData->data[1]->group)-1; $p++) {
        var_dump($page_data->extractorData->data[1]->group[$p]->Image[0]->href);
    }
}

die('Thanks for playing!');

//TODO Make this update all listings for a specific island and push into sql database
?>
