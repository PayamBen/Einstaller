#!/usr/bin/php
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

$WEBROOT = "/home/paz/websites/playground/junk/";
$SERVERNAME = "localhost";
$APACHEUSER = "wwwrun";
$MYSQLDB = "wp-example";
$MYSQLHOST = "127.0.0.1";
$MYSQLUSER = "root";
$MYSQLPWD = "";


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

echo('Downloading WordPress ...');
exec('wget http://wordpress.org/latest.tar.gz');
echo('done<br/>');
if(!file_exists('latest.tar.gz'))
{
    die('no wordpress file');
}

echo('Copying Wordpress...');
if (!copy('latest.tar.gz', $WEBROOT.'latest.tar.gz'))
{
    die('could not copy wordpress file');
}
echo('done<br />');


echo('Unpacking WordPresss ...');
// decompress from gz
$p = new PharData($WEBROOT.'latest.tar.gz');
$p->decompress(); // creates /path/to/my.tar

// unarchive from the tar
$phar = new PharData($WEBROOT.'latest.tar');
$phar->extractTo($WEBROOT);
if(!file_exists($WEBROOT))
{
    die('Extraction failed');
}
echo('done<br/>');
echo('Moving wordpress files outside wordpress folder...');

$files = glob($WEBROOT . 'wordpress/*');
foreach($files as $file)
{
    $file_to_go = str_replace($WEBROOT . 'wordpress',$WEBROOT,$file);
    rename($file, $file_to_go);
}
echo('done<br/>');
echo('Removing zip files ...');
unlink($WEBROOT . 'latest.tar');
unlink($WEBROOT . 'latest.tar.gz');
rmdir($WEBROOT . 'wordpress');
echo('done<br/>');

echo("Setting up the config file...");
//Now let's set up the config file
$config_file = file($WEBROOT . 'wp-config-sample.php');
$secret_keys = file_get_contents( 'https://api.wordpress.org/secret-key/1.1/salt/' );
$secret_keys = explode( "\n", $secret_keys );
foreach ( $secret_keys as $k => $v ) {
    $secret_keys[$k] = substr( $v, 28, 64 );
}
array_pop($secret_keys);

$config_file = str_replace('database_name_here', $MYSQLDB, $config_file);
$config_file = str_replace('username_here', $MYSQLUSER, $config_file);
$config_file = str_replace('password_here', $MYSQLPWD, $config_file);
$config_file = str_replace('localhost', $MYSQLHOST, $config_file);
$config_file = str_replace("'AUTH_KEY',         'put your unique phrase here'", "'AUTH_KEY',         '{$secret_keys[0]}'", $config_file);
$config_file = str_replace("'SECURE_AUTH_KEY',  'put your unique phrase here'", "'SECURE_AUTH_KEY',  '{$secret_keys[1]}'", $config_file);
$config_file = str_replace("'LOGGED_IN_KEY',    'put your unique phrase here'", "'LOGGED_IN_KEY',    '{$secret_keys[2]}'", $config_file);
$config_file = str_replace("'NONCE_KEY',        'put your unique phrase here'", "'NONCE_KEY',        '{$secret_keys[3]}'", $config_file);
$config_file = str_replace("'AUTH_SALT',        'put your unique phrase here'", "'AUTH_SALT',        '{$secret_keys[4]}'", $config_file);
$config_file = str_replace("'SECURE_AUTH_SALT', 'put your unique phrase here'", "'SECURE_AUTH_SALT', '{$secret_keys[5]}'", $config_file);
$config_file = str_replace("'LOGGED_IN_SALT',   'put your unique phrase here'", "'LOGGED_IN_SALT',   '{$secret_keys[6]}'", $config_file);
$config_file = str_replace("'NONCE_SALT',       'put your unique phrase here'", "'NONCE_SALT',       '{$secret_keys[7]}'", $config_file);

if(file_exists($WEBROOT .'wp-config.php')) {
    unlink($WEBROOT .'wp-config.php');
}

$fw = fopen($WEBROOT . 'wp-config.php', "a");

foreach ( $config_file as $line_num => $line ) {
    fwrite($fw, $line);
}

echo('done<br />Your WordPress site is ready');

?>