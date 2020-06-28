<?php 
class Source {
    
    public $conn;
    
    
    public function __construct()
    {
        try
        {
            $options = [
                'PDO::ATTR_EMULATE_PREPARES' => FALSE,
                'PDO::ATTR_ERRMODE' => 'PDO::ERRMODE_EXCEPTION',
            ];
            $this->conn = new \PDO('mysql:host=localhost;dbname=srgg', 'programmer', 'Dxn546b148.',$options);
        }
        catch (\PDOException $e)
        {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    
    public function parseStoreAPI($appID, $country = 'us')
    {
        
        
        $options = [
            CURLOPT_URL => "https://store.steampowered.com/api/appdetails?appids=$appID&cc=$country",
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
        ];
        $url = curl_init();
        curl_setopt_array($url, $options);
        $json = curl_exec($url);
        
        if(curl_errno($url) == 0)
        {
            $result = json_decode($json, false);
            return $result->$appID;
        }
        else
        {
            $error = curl_error($url);
            throw new \Exception($error);
        }
    }
    
    public function parseSteamAPI($interfaceName, $methodName, $versionID)
    {
        
        $options = [
            CURLOPT_URL => "http://api.steampowered.com/$interfaceName/$methodName/$versionID/?key=393B732436A1B117D49B72385BA4D7B2",
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
        ];
        $url = curl_init();
        curl_setopt_array($url, $options);
        $json = curl_exec($url);
        if(curl_errno($url) == 0)
        {
            $result = json_decode($json, false);
            return $result;
        }
        else
        {
            $error = curl_error($url);
            throw new \Exception($error);
        }
        
    }
}
?>