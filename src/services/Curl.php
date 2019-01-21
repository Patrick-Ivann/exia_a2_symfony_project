<?php


namespace App\services;




class Curl
{

    public function faireRequete($method, $url, $data = false)
    {

        $curlObject = curl_init();

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



        curl_exec($curlObject);


        curl_close($ch);
    }




    public function faireRequeteAvecHeader($method, $url, $token, $data = false)
    {
        $curlObject = curl_init();

        $header = array(
            "verification: e " . $token

        );
        switch ($method) {
            case 'POST':
                curl_setopt($curlObject, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

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



        curl_exec($curlObject);


        curl_close($ch);
    }



}



?>

