<?php
require '../core/ifIsSet.php';

require '../core/Source.php';

$init = new Source();
$isSet = new IfIsSet();
$db = $init;

/*        libxml_use_internal_errors(true);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $html = file_get_contents('https://store.steampowered.com/search/?sort_by=Released_DESC');
        $dom->loadHTML($html);
        $a = $dom->getElementById('search_results')->getElementsByTagName('a')->item(0)->getAttribute("data-ds-appid");
  */      

        
        $qa = $db->conn->prepare('SELECT * FROM `settings` WHERE `name` = ? ');
        $qa->execute(array('last_id_updated'));
        $row = $qa->fetch();        
        
        for($i = $row['value'] + 1; $i <= $row['value'] + 100; $i++)
            {
               $apps = $init->parseSteamAPI('ISteamApps','GetAppList','v0001');
               
               $result = $init->parseStoreAPI($apps->applist->apps->app[$i]->appid);
               
               if($i == ($row['value'] + 100))
                {
                   $q = $db->conn->prepare('UPDATE `settings` SET `value` = ? WHERE `name` = ? ');
                   $q->execute(array($i,'last_id_updated'));
                }
                
                 if($result->success === TRUE)
                  {
                    if($result->data->type == 'game')
                    {
                        $q = $db->conn->prepare('SELECT * FROM `games` WHERE `steam_appid` = ? ');
                        $q->execute(array($apps->applist->apps->app[$i]->appid));
                        $row_count = $q->rowCount();
                        
                        if($row_count == 0)
                        {
                            $data = $result->data;
                            $game = $data->name;
                            $game_id = (int)$data->steam_appid;
                            @$dlc = $isSet->verify($data->dlc,true);
                            $desc = strip_tags($data->detailed_description);
                            @$lang = $isSet->verify($data->supported_languages, false, false, true);
                            $profile_img = $data->header_image;
                            @$requirements = $isSet->verify($data->pc_requirements->minimum, false, false, true);
                            @$demos = $isSet->verify($data->demos,true);
                            if($data->is_free == TRUE) 
                            {
                                $price = 0;
                            }
                            else 
                            {
                                $price = $isSet->verify($data->price_overview->initial);
                            }
                            $platforms = json_encode($data->platforms);
                            @$categories = $isSet->verify($data->categories,true);
                            @$genres = $isSet->verify($data->genres,true);
                            $coming_soon = $data->release_date->coming_soon?1:0;
                            $release = strtotime($data->release_date->date);
                            
                              $q = $db->conn->prepare('INSERT INTO `games`(name, steam_appid, dlc, demos, description,
                                                                           language, profile_img, requirements,
                                                                           price, platforms, categories,
                                                                           genres, coming_soon, release_date) 
                                                       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
                            
                              $q->execute(array($game, $game_id, $dlc, $demos, $desc,
                                                $lang, $profile_img, $requirements,
                                                $price, $platforms, $categories,
                                                $genres, $coming_soon, $release));
                        }
                        
                      }
                  } 
            }
  
   