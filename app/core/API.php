<?php 
class API {

    public function parseStore($query_string)
    {
        $options = [
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => "https://store.steampowered.com/$query_string"
        ];

        $url = curl_init();
        curl_setopt_array($url, $options);
        $json = curl_exec($url);

        if(curl_errno($url) == 0) {
            $result = json_decode($json, false);
            return $result;
        }
        else {
            $error = curl_error($url);
            throw new \Exception("API ERROR: $error");
        }
        return NULL;
    }

    public function parseSteam($interfaceName, $methodName, $versionID)
    {
        $options = [
            CURLOPT_URL => "https://api.steampowered.com/$interfaceName/$methodName/$versionID/?key=700D6A7555C37B6C24B3CF56583B47D0",
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
        ];
        $url = curl_init();
        curl_setopt_array($url, $options);
        $json = curl_exec($url);
        if(curl_errno($url) == 0) {
            $result = json_decode($json, false);
            return $result;
        }
        else {
            $error = curl_error($url);
            throw new \Exception("API ERROR: $error");
        }
        return NULL;
    }
}
