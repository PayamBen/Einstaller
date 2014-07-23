<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


include_once('header.php');
?>
<h1>Auto Script Installer</h1>
    <div id="tile">
        <a href="script.php"><img src="img/wordpress.jpg" /></a>
        <p class="center heading">Wordpress</p>
    </div>
    <div id="tile">
        <a href="#"><img src="img/Joomla.jpg" /></a>
        <p class="center heading">Joomla</p>
    </div>
    <div id="tile">
        <a href="#"><img src="img/druplicon.jpg" /></a>
        <p class="center heading">Drupal</p>
    </div>
     <div id="tile">
        <a href="#"><img src="img/magento.jpg" /></a>
        <p class="center heading">Magento</p>
    </div>
    
<?php

$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("einstaller") or die(mysql_error());
$result = mysql_query("SELECT * FROM installs") or die(mysql_error());
?>
<div id="listScr">
<h1>Installed Scripts</h1>
<?php
while($row = mysql_fetch_array($result)){
	echo $row['id']. " - ". $row['created'];
	echo "<br />";
}?>
</div>
<?php
mysql_close($link);
include_once('footer.php');
?>