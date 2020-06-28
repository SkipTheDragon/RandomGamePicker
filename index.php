<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'app/init.php';

$app = new App();

require_once 'app/views/partials/header.php';

$app->dispatch();

require_once 'app/views/partials/footer.php';
