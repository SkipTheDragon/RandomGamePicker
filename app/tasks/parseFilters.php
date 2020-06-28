<?php

require '../core/ifIsSet.php';

require '../core/API.php';

require '../core/DataBase.php';

$db = new DataBase();
$isSet = new IfIsSet();


libxml_use_internal_errors(true);

/*
$dom = new DOMDocument('1.0', 'UTF-8');
$html = file_get_contents('https://store.steampowered.com/tag/browse#global_492');
$dom->loadHTML($html);
$index = $dom->getElementById('tag_browse_global')->getElementsByTagName('div')->length;
for ($i = 0; $i < $index; $i++) {
    $a = $dom->getElementById('tag_browse_global')->getElementsByTagName('div')->item($i)->textContent;
    
    $qa = $db->conn->prepare('INSERT INTO `filters` (type,value) VALUES (?,?)');
    $qa->execute(array('genres',$a));
    
}

*/

?>