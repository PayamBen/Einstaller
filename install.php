<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
include_once('header.php');
if (!isset($_GET['name'])) {
    echo "<h1>Nothing to install!</h1>";
    die();
}
echo "<h1>Installing " . $_GET['name'] . "</h1>"; ?>
<form>
    <fieldset>
        <label>Where would you like to install <?php echo $_GET['name']; ?>?</label>
        <select name="cars">
        <option value="volvo">Volvo</option>
        <option value="saab">Saab</option>
        <option value="fiat">Fiat</option>
        <option value="audi">Audi</option>
        </select>
    </fieldset>
    
    
</form>

<?php
/*
if($_GET['name'] == 'Wordpress') { ?>
    <p><?php system('scripts/wordpress.php'); ?></p>
    <?php
}elseif ($_GET['name'] == 'Joomla')
{
    system('scripts/joomla.php');   
}else
{
    echo "No matching script";
}*/
include_once('footer.php'); 
?>