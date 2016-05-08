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

$page_number = 1;
$island_number = 3;
$district = '';
$baseUrl = 'https://extraction.import.io/query/extractor/'. $config_importio_hiis_GUID_search .'?_apikey='. $config_importio_apikey .'&url=http%3A%2F%2Fwww.alohaliving.com%2Fsearch%2F%3Fpage%3D'. $page_number .'%26ipp%3D100%26island%3D'. $island_number .'%26District%3D'. $district .'%26minprice%3D0%26maxprice%3D9999999999999%26minbeds%3D0%26minbaths%3D0';

$first_page_data = json_decode(file_get_contents($baseUrl));
$total_pages = intval($first_page_data->extractorData->data[0]->group[0]->{'Amount of Pages'}[0]->text);

for($i = 1; $i <= 2; $i++) { //replace with $total_pages later
    $page_number = $i;
    $fetchUrl = 'https://extraction.import.io/query/extractor/'. $config_importio_hiis_GUID_search .'?_apikey='. $config_importio_apikey .'&url=http%3A%2F%2Fwww.alohaliving.com%2Fsearch%2F%3Fpage%3D'. $page_number .'%26ipp%3D100%26island%3D'. $island_number .'%26District%3D'. $district .'%26minprice%3D0%26maxprice%3D9999999999999%26minbeds%3D0%26minbaths%3D0';

    $page_data = json_decode(file_get_contents($fetchUrl));
    var_dump($page_data->extractorData->data);
    for($l = 1; $l <= 100; $l++) {

    }
}

//TODO Make this update all listings for a specific island and push into sql database
?>
