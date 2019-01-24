<?php


namespace App\Services;


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
        curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlObject);


        if (curl_exec($curlObject) === false) {
            echo 'Erreur Curl : ' . curl_error($curlObject);
        }
        if (!curl_errno($curlObject)) {
            switch ($http_code = curl_getinfo($curlObject, CURLINFO_HTTP_CODE)) {
                case 200:
                    break;
                default:
                    echo 'Erreur HTTP: ', $http_code, "\n";
            }
        }
        curl_close($curlObject);
        //dump(curl_getinfo($curlObject, CURLINFO_HTTP_CODE));
        $result[] = substr($response, 0);
        return $result[0];
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
        $curlObject = curl_init();

        curl_setopt($curlObject, CURLOPT_URL, $url);

        $header = array(
            "verificateur: e " . $token
        );

        switch ($method) {
            case 'POST':
                curl_setopt($curlObject, CURLOPT_POST, 1);
                curl_setopt($curlObject, CURLOPT_HTTPHEADER, $header);
                if ($data)
                    curl_setopt($curlObject, CURLOPT_POSTFIELDS, $data);
                break;


            case 'DELETE':
                if ($data) {
                }
                curl_setopt($curlObject, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curlObject, CURLOPT_HTTPHEADER, $header);
                break;

            default:
                curl_setopt($curlObject, CURLOPT_URL, $url);
                curl_setopt($curlObject, CURLOPT_HEADER, $header);
                break;
        }
        curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlObject, CURLOPT_HEADER, false);
        $response = curl_exec($curlObject);
        if (curl_exec($curlObject) === false) {
            echo 'Erreur Curl : ' . curl_error($curlObject);
        }
        if (!curl_errno($curlObject)) {
            switch ($http_code = curl_getinfo($curlObject, CURLINFO_HTTP_CODE)) {
                case 200:
                    break;
                default:
                    echo 'Erreur HTTP: ', $http_code, "\n";
            }
        }
        curl_close($curlObject);
        //dump(curl_getinfo($curlObject, CURLINFO_HTTP_CODE));
        $result[] = substr($response, 0);
        return $result[0];
    }
    public function faireRequeteAvecFichier($method, $url, $token, $data = false, $path, $type)
    {
        $curlObject = curl_init();
        curl_setopt($curlObject, CURLOPT_URL, $url);
        curl_setopt($curlObject, CURLOPT_VERBOSE, 1);
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
                if ($data) {
                    if ($type === 'produit') {
                        $arr = json_decode($data, true);
                        $b = $arr["nom_produit"];
                        \dump($arr["nom_produit"]);
                        $nomFichier = $b . '.' . $path->guessExtension();
                        $postfields = array(
                            $path->guessExtension() => new \CURLFile($path, $path->getMimeType(), $nomFichier),
                            'formulaire' => $data
                        );
                    } else {
                        $postfields = array(
                            $path->guessExtension() => new \CURLFile($path, $path->getMimeType(), 'petit.png'),
                            'formulaire' => $data
                        );
                    }
                    //  $postfields["upfile"] = "$path";
                    curl_setopt($curlObject, CURLOPT_POSTFIELDS, $postfields);
                }
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
        $response = curl_exec($curlObject);
        //dump(curl_getinfo($curlObject, CURLINFO_HTTP_CODE));
        if (curl_exec($curlObject) === false) {
            echo 'Erreur Curl : ' . curl_error($curlObject);
        }
        if (!curl_errno($curlObject)) {
            switch ($http_code = curl_getinfo($curlObject, CURLINFO_HTTP_CODE)) {
                case 200:
                    break;
                default:
                    echo 'Erreur HTTP: ', $http_code, "\n";
            }
        }
        $result[] = substr($response, 0);
        return $result[0];
    }
}
?>
