<?php
if($imageStatus == true) {?>
    <style>
        .header-items {
            background-image:url('https://steamcdn-a.akamaihd.net/steam/apps/<?php echo  $getGameDetails['steam_appid'];?>/page_bg_generated_v6b.jpg');
        }
    </style>
<?php } else { ?>
    <style>
        .header-items {
            background-image:url('https://steamcdn-a.akamaihd.net/steam/apps/563500/page_bg_generated_v6b.jpg');
        }
    </style>
<? } ?>
<div class="game-wrapper">
<div class="game-box">
    <div class="header-items">
      <div class="center-items">
        <img src="<?php echo  $getGameDetails['profile_img'];?>" width="200">
          <br/>
        <h2><?php echo  $getGameDetails['name'];?></h2>
      </div>
    </div>

    <div class="content">
        <div class="description">
            <h3>About the game</h3>
            <p><?php echo $getGameDetails['description'];?></p>
        </div>
    </div>
    <div class="menu">
        <h3>Details</h3>
        <div id="more"><span>. . .</span></div>
        <h5>Price:
            <?php if(is_null($price) || $price == 0){
                echo 'Free';
            } else {
                echo number_format(($price /100), 2, '.', ' '). ' EUR';
            }  ?>
        </h5>
        <h5>Rating: <?php echo $rating; ?></h5>
        <h5>
            <?php if(!empty($num_of_players)){ ?>
                <div class="categories">Num. Of Players:
                    <?php
                    foreach ($num_of_players as $description)
                    {
                        echo '<span>'.$description. '</span> ';
                    }
                    ?>
                </div>
            <?php } ?>
        </h5>
        <h5>
            <?php if(!empty($genres)){ ?>
                <div class="categories">Genres:
                    <?php
                    foreach ($genres as $description)
                    {
                        echo '<span>'.$description. '</span> ';
                    }
                    ?>
                </div>
            <?php } ?>
        </h5>
        <h5>
            <?php if(!empty($categories)){ ?>
                <div class="categories">Features:
                    <?php
                    foreach ($categories as $description)
                    {
                        echo '<span>'.$description. '</span> ';
                    }
                    ?>
                </div>
            <?php } ?>
        </h5>
        <div class="right-buttons">
            <a href="https://store.steampowered.com/app/<?php
            $link_names = preg_replace('/\s+/', '_', $getGameDetails['name']);
            echo $getGameDetails['steam_appid']. '/' .$link_names ?>" target="_blank"><div class="right-button"><span>Go To Store Page</span></div></a>
            <br/>
            <a href="https://rgame.devtheunknown.com/simple?auto_start=1" title="Without filters"> <div class="right-button" style="background-image: linear-gradient(to right, #3897EB, #235ECF);"><span>Pick Another Game</span></div></a>
        </div>
    </div>
</div>
</div>
