<?php

namespace App\services;

/**
 * Class Curl
 * @package App\services
 */
class Curl
{

    /**
     * @param $method
     * @param $url
     * @param bool $data
     * @return mixed
     */
    public function faireRequete($method, $url, $data = false)
    {
        $curlObject = curl_init(); // Initialise the curl request

        curl_setopt($curlObject, CURLOPT_URL, $url); // Set URL option

        switch ($method) {
            case 'POST': // If this is a post request
                curl_setopt($curlObject, CURLOPT_POST, 1); // Set option POST

                if ($data) {
                    curl_setopt($curlObject, CURLOPT_POSTFIELDS, $data);
                }
                break;

            default:
                curl_setopt($curlObject, CURLOPT_URL, $url);
                break;
        }

        curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, true); // Set another option
        $response = curl_exec($curlObject); // Execute the request

        // Handle curl response error
        if ($response === false) {
            echo 'Erreur Curl : ' . curl_error($curlObject);
        }

        // Handle error status code
        if (!curl_errno($curlObject)) {
            switch ($http_code = curl_getinfo($curlObject, CURLINFO_HTTP_CODE)) {
                case 200:
                    break;
                default:
                    echo 'Erreur HTTP: ', $http_code, "\n";
            }
        }

        curl_close($curlObject); // Free the memory used by the request

        $result[] = substr($response, 0); // Parse the JSON response
        return $result[0]; // Return the response
    }

    /**
     * @param $method
     * @param $url
     * @param $token
     * @param bool $data
     * @return mixed
     */
    public function faireRequeteAvecHeader($method, $url, $token, $data = false)
    {
        $curlObject = curl_init(); // Initialise the curl request

        curl_setopt($curlObject, CURLOPT_URL, $url); // Set URL option

        // Define the header
        $header = array(
            "verificateur: e " . $token
        );

        switch ($method) {
            case 'POST': // POST requests
                curl_setopt($curlObject, CURLOPT_POST, 1);
                curl_setopt($curlObject, CURLOPT_HTTPHEADER, $header);

                if ($data) {
                    curl_setopt($curlObject, CURLOPT_POSTFIELDS, $data);
                }
                break;

            case 'DELETE': // DELETE requests
                curl_setopt($curlObject, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curlObject, CURLOPT_HTTPHEADER, $header);
                break;

            default: // GET and PUT requests
                curl_setopt($curlObject, CURLOPT_URL, $url);
                curl_setopt($curlObject, CURLOPT_HEADER, $header);
                break;
        }

        // Set different options
        curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlObject, CURLOPT_HEADER, false);

        $response = curl_exec($curlObject); // Execution of the curl request

        // Handle curl response error
        if ($response === false) {
            echo 'Erreur Curl : ' . curl_error($curlObject);
        }

        // Handle error status code
        if (!curl_errno($curlObject)) {
            switch ($http_code = curl_getinfo($curlObject, CURLINFO_HTTP_CODE)) {
                case 200:
                    break;
                default:
                    echo 'Erreur HTTP: ', $http_code, "\n";
            }
        }

        curl_close($curlObject); // Free the memory used by the request

        $result[] = substr($response, 0); // Parse the JSON response
        return $result[0]; // Return the response
    }

    /**
     * @param $method
     * @param $url
     * @param $token
     * @param bool $data
     * @param $path
     * @param $type
     * @return mixed
     */
    public function faireRequeteAvecFichier($method, $url, $token, $data = false, $path, $type)
    {
        $curlObject = curl_init(); // Initialise the curl request

        // Set different options
        curl_setopt($curlObject, CURLOPT_URL, $url);
        curl_setopt($curlObject, CURLOPT_VERBOSE, 1);

        $header = array(
            "verificateur: e " . $token
        );

        switch ($method) {
            case 'POST':
                curl_setopt($curlObject, CURLOPT_POST, 1);
                curl_setopt($curlObject, CURLOPT_HTTPHEADER, $header);

                if ($data) {

                    if ($type === 'produit') { // POST request for a product
                        $arr = json_decode($data, true);
                        $b = $arr["nom_produit"];
                        \dump($arr["nom_produit"]);
                        $nomFichier = $b . '.' . $path->guessExtension();
                        $postfields = array(
                            $path->guessExtension() => new \CURLFile($path, $path->getMimeType(), $nomFichier),
                            'formulaire' => $data
                        );
                    } else {
                        $decoded_data = json_decode($data);
                        $postfields = array(
                            $path->guessExtension() => new \CURLFile($path, $path->getMimeType(), $decoded_data->id_event . '_' . date("YmdHis") . '.png'),
                            'formulaire' => $data
                        );
                    }
                    curl_setopt($curlObject, CURLOPT_POSTFIELDS, $postfields);
                }
                break;

            case 'DELETE': // DELETE requests
                if ($data)
                    curl_setopt($curlObject, CURLOPT_POSTFIELDS, $data);
                break;

            default:
                curl_setopt($curlObject, CURLOPT_URL, $url);
                curl_setopt($curlObject, CURLOPT_HEADER, $header);
                break;
        }

        curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curlObject); // Execute the request

        // Handle curl error
        if ($response === false) {
            echo 'Erreur Curl : ' . curl_error($curlObject);
        }

        // handle status code errors
        if (!curl_errno($curlObject)) {
            switch ($http_code = curl_getinfo($curlObject, CURLINFO_HTTP_CODE)) {
                case 200:
                    break;
                default:
                    echo 'Erreur HTTP: ', $http_code, "\n";
            }
        }

        $result[] = substr($response, 0); // Parse the JSON response
        return $result[0]; // Return the response
    }
}
