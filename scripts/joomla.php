#!/usr/bin/php
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

$WEBROOT = "/home/paz/websites/playground/junk/";
$SERVERNAME = "localhost";
$APACHEUSER = "wwwrun";
$MYSQLDB = "jl-example";
$MYSQLHOST = "127.0.0.1";
$MYSQLUSER = "root";
$MYSQLPWD = "";
$FILENAME = 'Joomla_3.3.2-Stable-Full_Package.zip';
$FULLPATH = 'http://joomlacode.org/gf/download/frsrelease/19639/159961/Joomla_3.3.2-Stable-Full_Package.zip';


$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Could not connect to database;' . mysql_error());
}

//check WEBROOT for content allow for . & ..
if (count(scandir($WEBROOT)) > 2)
{
    die("Error (" . $WEBROOT . ") is not empty");
}

//Check that $MYSQLDB is empty
$sql = "SHOW TABLES FROM `" . $MYSQLDB ."`";
$result = mysql_query($sql);
echo $result;

if ($result != false) {
    if(mysql_num_rows($result) > 0)
    {
        die("Database (" . $MYSQLDB . ") is not empty");
    }
}

echo('Creating DB ...');
    $sql = 'CREATE DATABASE IF NOT EXISTS `'. $MYSQLDB . '`;';
    if (!mysql_query($sql, $link))
    {
        die(mysql_error("could not create db"));
    }

echo('done<br/>');

chdir('downloads');

//echo('Downloading Joomla ...');
//exec('wget ' . $FULLPATH);
//echo('done<br/>');
if(!file_exists($FILENAME))
{
    die('no Joommla file');
}

echo('Copying Joomla...');
if (!copy($FILENAME, $WEBROOT. $FILENAME))
{
    die('could not copy Joomla file');
}
echo('done<br />');


echo('Unpacking Joomla ...');
// decompress from zip
$zip = new ZipArchive;
$res = $zip->open($WEBROOT. $FILENAME);
if ($res === TRUE) {
  $zip->extractTo($WEBROOT);
  $zip->close();
}


if(!file_exists($WEBROOT))
{
    die('Extraction failed');
}
echo('done<br/>');

echo('Removing zip file ...');
unlink($WEBROOT . $FILENAME);
echo('done<br/>');

echo('Your Joomla site is almost ready');
echo('<hr>');
echo('Database Name:' . $MYSQLDB . '<br/>');
?>