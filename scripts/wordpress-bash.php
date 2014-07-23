 <?php
echo('Creating DB ...');
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
$tab = (chr(9));

$WEBROOT = "/home/paz/websites/playground/junk/";
//$VHOSTPATH = "/etc/apache2/vhosts.d/test.conf";
$SERVERNAME = "localhost";
$APACHEUSER = "wwwrun";
$MYSQLDB = "wp-example";
$MYSQLHOST = "127.0.0.1";
$MYSQLUSER = "root";
$MYSQLPWD = "";

//Need this to emulate the browser-based installation
$_SERVER['HTTP_HOST'] = $SERVERNAME;
$_SERVER['REQUEST_URI'] = "/";

echo('Creating DB ...');
if(strlen($MYSQLPWD)) {
    exec("mysql -h" . $MYSQLHOST . " -u" . $MYSQLUSER . " -p" . $MYSQLPWD . " -e  'CREATE DATABASE IF NOT EXISTS '" . $MYSQLDB . ";");
} else {
    exec("mysql -h" . $MYSQLHOST . " -u" . $MYSQLUSER . " -e  'CREATE DATABASE IF NOT EXISTS '" . $MYSQLDB . ";");
}

//echo('Downloading WordPress ...');
//exec('wget http://wordpress.org/latest.tar.gz');

if(!file_exists('latest.tar.gz'))
{
    die('no wordpress file');
}

echo('Copying Wordpress...');
if (!copy('latest.tar.gz', $WEBROOT.'latest.tar.gz'))
{
    die('could not copy wordpress file');
}
echo('success<br />');
echo('Unpacking WordPresss ...<br/>');
exec('tar -xzf latest.tar.gz');

exec('rm -rf wordpress');

echo("Setup folder permissions..<br/>");
//set folder permissions to apache user
exec('chown -R ' . $APACHEUSER . ':staff ' . $WEBROOT);

//add local site to the hosts file
//echo("Add entry in /etc/hosts file...");
//exec('echo "127.0.0.1\t' . $SERVERNAME . '" >> /etc/hosts');

//echo("Setting up the vhost...");
//set up apache vhost
//$VHOST='NameVirtualHost *:80' . PHP_EOL . PHP_EOL;
//$VHOST.='<Directory ' . $WEBROOT . '>' . PHP_EOL;
//$VHOST.=$tab . 'Options Indexes FollowSymLinks MultiViews' . PHP_EOL;
//$VHOST.=$tab . 'AllowOverride All' . PHP_EOL;
//$VHOST.=$tab . 'Order allow,deny' . PHP_EOL;
//$VHOST.=$tab . 'Allow from all' . PHP_EOL;
//$VHOST.='' . PHP_EOL;

//$VHOST.='' . PHP_EOL;
//$VHOST.=$tab . 'DocumentRoot ' . $WEBROOT . PHP_EOL;
//$VHOST.=$tab . 'ServerName ' . $SERVERNAME . PHP_EOL;
//$VHOST.=$tab . 'DirectoryIndex index.php' . PHP_EOL;
//$VHOST.='';

//$fw = fopen($VHOSTPATH, "w");
//fwrite($fw, $VHOST);

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

echo("Installing WordPress...");
define('ABSPATH', $WEBROOT);
define('WP_CONTENT_DIR', 'wp-content/');
define('WPINC', 'wp-includes');
define( 'WP_LANG_DIR', WP_CONTENT_DIR . '/languages' );

define('WP_USE_THEMES', true);
define('DB_NAME', $MYSQLDB);
define('DB_USER', $MYSQLUSER);
define('DB_PASSWORD', $MYSQLPWD);
define('DB_HOST', $MYSQLHOST);

#$_GET['step'] = 2;
#$_POST['weblog_title'] = "My Test Blog";
#$_POST['user_name'] = "admin";
#$_POST['admin_email'] = "[email protected]";
#$_POST['blog_public'] = true;
#$_POST['admin_password'] = "admin";
#$_POST['admin_password2'] = "admin";

#require_once(ABSPATH . 'wp-admin/install.php');
#require_once(ABSPATH . 'wp-load.php');
#require_once(ABSPATH . WPINC . '/class-wp-walker.php');
#require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

//echo('restarting apache');
//exec('apachectl -k graceful');
echo('Your WordPress site is ready');

?>