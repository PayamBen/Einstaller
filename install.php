<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
if (!isset($_GET['name'])) {
    echo "<h1>Nothing to install!</h1>";
    die();
}

if($_GET['name'] == 'wordpress') {
    echo "<h1>Installing " . $_GET['name'] . "</h1>";
    if(exec('scripts/wordpress.php'))
    {
         echo "<h1>Installation Complete</h1>";
    }
    else
    {
        echo "<h1>Installation Failed</h1>";
    }
    die();
}

?>