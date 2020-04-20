<?php

require_once APP_DIR . '/Models/Api.php';

class AdminController
{
    private $apiUrl = 'https://covid-19-coronavirus-statistics.p.rapidapi.com/v1/stats';
    private $rapidapi_host = 'x-rapidapi-host: covid-19-coronavirus-statistics.p.rapidapi.com';
    private $rapidapi_key = 'x-rapidapi-key: 42f866a9bamsh624642e94bc9f48p155f6ejsn5686cb413636';

    public function connectToApi()
    {
        $api = new Api($this->apiUrl, $this->rapidapi_host, $this->rapidapi_key);
        $api->connectToApi();
        $json = json_decode($api->getResponse(), true);
        return $json;
    }


    public function connectToDb()
    {
        //mysqli_connect('127.0.0.1', 'root', '', 'covid19db');
        return new PDO('mysql:host=127.0.0.1;dbname=covid19db', 'root', '');
    }

    public function setDataToDB()
    {
        $json = $this->connectToApi();
        $country_stats = $json['data']['covid19Stats'];

        $this->deleteData();

        for ($i = 0; $i < count($country_stats); $i++) {
            try {
                $sql_add_all_stats = "INSERT INTO country_stats(city, province, country, last_update, confirmed, deaths, recovered) VALUES ('" . $country_stats["$i"]['city'] . "', '" . $country_stats["$i"]['province'] . "', '" . $country_stats["$i"]['country'] . "', '" . $country_stats["$i"]['lastUpdate'] . "', " . $country_stats["$i"]['confirmed'] . " , " . $country_stats["$i"]['deaths'] . ", " . $country_stats["$i"]['recovered'] . ")";
                $this->connectToDb()->query($sql_add_all_stats);
            } catch (Exception $e) {
                echo $e;
            }
        }
    }

    public function deleteData()
    {
        try {
            $sql_delete_all_stats = "DELETE FROM country_stats";
            $sql_zeroing = "ALTER TABLE country_stats AUTO_INCREMENT=0";
            $this->connectToDb()->query($sql_delete_all_stats);
            $this->connectToDb()->query($sql_zeroing);
        } catch (Exception $e) {
            echo 'Error: ' . $e;
        }
    }

    public function getStats()
    {

//        $this->setDataToDB();

        try {
            $sql_get_all_data = "SELECT city, province, country, last_update, confirmed, deaths, recovered FROM country_stats";
            $result = $this->connectToDb()->query($sql_get_all_data);
            $json_array = array();

            $cnr = 0;
            if (!isset($result)) {
                echo 'Result is undefined';
            }
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $json_array[] = $row;
                ++$cnr;
            }
            echo json_encode(array(
                'success' => true,
                'rows' => $json_array,
                'count' => $cnr
            ));
//            $json_string = json_encode($json_array);
//            echo $json_string;
//
//            if (isset($json_string)) {
//                return $json_string;
//            }
        } catch (Exception $e) {
            // echo $e;
            echo json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            ));
        }

    }

    public function importCountries()
    {
        try {
            $sql_get_countries = "SELECT country FROM country_stats GROUP BY country";
            $result = $this->connectToDb()->query($sql_get_countries);
            if (!isset($result)) {
                echo 'Result is undefined';
            }

            $json_array = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $json_array[] = $row;
            }
            $json_string = json_encode($json_array);

            if (isset($json_string)) {
                return $json_string;
            }
        } catch (Exception $e) {
            echo $e;
        }

    }




}