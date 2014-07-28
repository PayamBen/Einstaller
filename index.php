<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


include_once('header.php');

$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("einstaller") or die(mysql_error());
$installResult = mysql_query("SELECT installs.id, script.name, installs.created, installs.path FROM installs INNER JOIN script ON installs.script_id=script.id;") or die(mysql_error());
mysql_select_db("einstaller") or die(mysql_error());
$scriptResult = mysql_query("SELECT * FROM script") or die(mysql_error());
?>
<div id="listScr">
<h1>Installed Scripts</h1>
<table>
    <tr>
        <th>
            ID
        </th>
        <th>
            Type
        </th>
        <th>
            Created
        </th>
         <th>
            Path
        </th>
        <th>
            Actions
        </th>
    </tr>
<?php
while($row = mysql_fetch_array($installResult)){
    echo "<tr>";    
    echo "<td>" . $row['id']. "</td>";
    echo "<td>". $row['name']. "</td>";
    echo "<td>". $row['created']. "</td>";
    echo "<td>". $row['path']. "</td>";
    echo "<td></td>";
    echo "</tr>";
}?>
</table>
</div>

<div>
    <h1>Available Scripts</h1>
    <table>
    <tr>
        <th>
            Name
        </th>
        <th>
            Description
        </th>
        <th>
            Version
        </th>
        <th>
            Actions
        </th>
    </tr>
<?php
while($row = mysql_fetch_array($scriptResult)){
    echo "<tr>";    
    echo "<td>". $row['name']. "</td>";
    echo "<td>".  substr($row['description'],0,100) . "</td>";
     echo "<td>". $row['version']. "</td>";
    echo "<td><a href='install.php?name=" . $row['name'] ."'>Install</a></td>";
    echo "</tr>";
}?>
</table>
</div>
<?php
mysql_close($link);
include_once('footer.php');
?>