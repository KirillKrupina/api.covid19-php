<?php

//include('Api.php');
//

//
//$api = new Api("https://covid-19-coronavirus-statistics.p.rapidapi.com/v1/stats", "x-rapidapi-host: covid-19-coronavirus-statistics.p.rapidapi.com", "x-rapidapi-key: 42f866a9bamsh624642e94bc9f48p155f6ejsn5686cb413636");
//$api->connectToApi();
//
//$json = json_decode($api->getResponse(), true);
//$country_stats = $json['data']['covid19Stats'];
//
//$connect = mysqli_connect('127.0.0.1', 'root', '', 'covid19db');
//
//
//try {
//    $sql_delete_all_stats = "DELETE FROM country_stats";
//    $sql_zeroing = "ALTER TABLE country_stats AUTO_INCREMENT=0";
//    mysqli_query($connect, $sql_delete_all_stats);
//    mysqli_query($connect, $sql_zeroing);
//} catch (Exception $e) {
//    echo 'Error: ' . $e;
//}
//
//
//for ($i = 0; $i < count($country_stats); $i++) {
//
//    try {
//        $sql_add_all_stats = "INSERT INTO country_stats(city, province, country, last_update, confirmed, deaths, recovered) VALUES ('" . $country_stats["$i"]['city'] . "', '" . $country_stats["$i"]['province'] . "', '" . $country_stats["$i"]['country'] . "', '" . $country_stats["$i"]['lastUpdate'] . "', " . $country_stats["$i"]['confirmed'] . " , " . $country_stats["$i"]['deaths'] . ", " . $country_stats["$i"]['recovered'] . ")";
//        mysqli_query($connect, $sql_add_all_stats);
//    } catch (Exception $e) {
//        echo $e;
//    }
//}
//
////--------------------------------------------
//try {
//    $sql_get_data = "SELECT city, province, country, last_update, confirmed, deaths, recovered FROM country_stats";
//    $result = mysqli_query($connect, $sql_get_data);
//} catch (Exception $e) {
//    echo $e;
//}
//
//$json_array = array();
//while ($row = mysqli_fetch_assoc($result)) {
//    $json_array[] = $row;
//}
//
//$json_string = json_encode($json_array);
//echo $json_string;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

define('APP_DIR', __DIR__);

// подключаем необходимые файлы
require_once 'Classes/Router.php';

// подключаем конфигурацию URL
$routes = include(dirname(__FILE__) . '/routes.php');

// запускаем роутер
$router = new Router($routes);

try {
    $router->run();
} catch (Exception $e) {
    echo 'Error: #' . $e;
}

