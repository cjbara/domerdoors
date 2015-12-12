<?php
$servername = "localhost";
$username = "cjbara";
$password = "database";
$dbname = "domerdoors";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//Create variables
$netID = "'".$_POST['netID']."'";

$sql = "DELETE FROM Resident where  $netID=netID";

if (mysqli_query($conn, $sql)) {
    echo "User $netID Deleted";
} else {
    echo "Error: Could not delete user $netID";
}

mysqli_close($conn);

?>
