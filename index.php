<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(-1);
require_once 'app/init.php';

$app = new App();
//$sessions = new Sessions();

require_once 'app/views/partials/header.php';

//$sessions->index();
$app->dispatch();

require_once 'app/views/partials/footer.php';

// todo Add Menu && Make the site for mobile phones too && GRPD