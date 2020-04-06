<?php


class ApiConnect
{
    private $url;
    private $rapidapi_host;
    private $rapidapi_key;
    private $response;

    function __construct($url, $rapidapi_host, $rapidapi_key)
    {
        $this->url = $url;
        $this->rapidapi_host = $rapidapi_host;
        $this->rapidapi_key = $rapidapi_key;
    }

    public function connectToApi()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                $this->rapidapi_host,
                $this->rapidapi_key
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if($err) {
            echo 'cURL Error #:' . $err;
        }

        $this->response=$response;
    }

    public function getResponse() {
        return $this->response;
    }


}