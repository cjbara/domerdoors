<?php
// Connect and select database
$link = mysqli_connect("localhost", "dlewis12", "db2017","domerdoors")
	or die ("Connection failed: " .mysqli_connect_error());

//Get login info
$netID = "'".$_REQUEST['netID']."'";
$pwd = "'".$_REQUEST['password']."'";

//query for seeing if ID and pwd match database
$query = "select netID, password from Resident where Resident.netID = $netID and Resident.password = $pwd;";

//if match. go to user homepage
$result = mysqli_query($link, $query);
if(mysqli_num_rows($result) > 0) {
    //go to user prefs page
    $url = 'userPrefs.php';
    $params = 'netID='.$netID.'&password='.$pwd;

    header('Location: '.$url.'?'.$params);
    
} else {
    echo "Error: Could not login with netID: $netID";
}

mysqli_close($link);
?>

