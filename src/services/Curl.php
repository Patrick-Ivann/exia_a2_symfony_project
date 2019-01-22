<?php


namespace App\services;




class Curl
{

    public function faireRequete($method, $url, $data = false)
    {

        $curlObject = curl_init();

        curl_setopt($curlObject, CURLOPT_URL, $url);

        switch ($method) {
            case 'POST':
                curl_setopt($curlObject, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curlObject, CURLOPT_POSTFIELDS, $data);
                break;

            default:

                curl_setopt($curlObject, CURLOPT_URL, $url);

                break;
        }



        return curl_exec($curlObject);


        curl_close($curlObject);
    }




    public function faireRequeteAvecHeader($method, $url, $token, $data = false)
    {
        $curlObject = curl_init();

        curl_setopt($curlObject, CURLOPT_URL, $url);


        $header = array(
            "verificateur: e " . $token
        );

        switch ($method) {
            case 'POST':

                curl_setopt($curlObject, CURLOPT_POST, 1);
                //curl_setopt($curlObject, CURLOPT_HTTPHEADER, $header);

                curl_setopt($curlObject, CURLOPT_HTTPHEADER, array(
                    /*'X-Apple-Tz: 0',
                    'X-Apple-Store-Front: 143444,12'*/
                    "verificateur: e " . $token
                ));

                if ($data)
                    curl_setopt($curlObject, CURLOPT_POSTFIELDS, $data);

                break;

            case 'DELETE':
                if ($data)
                    curl_setopt($curlObject, CURLOPT_POSTFIELDS, $data);

                break;


            default:

                curl_setopt($curlObject, CURLOPT_URL, $url);
                curl_setopt($curlObject, CURLOPT_HEADER, $header);

                break;
        }

        curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlObject, CURLOPT_HEADER, false);
        $response = curl_exec($curlObject);
        curl_close($curlObject);

        //dump(curl_getinfo($curlObject, CURLINFO_HTTP_CODE));

        $result[] = substr($response,0);
        return $result[0];
    }



}

