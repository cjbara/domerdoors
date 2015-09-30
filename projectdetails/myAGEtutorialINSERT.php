<html>
<title></title>
<body>
<p>Here is the age data:</p>
<?php
//Connecting... selecting database
$link = mysqli_connect('localhost', 'dlewis12', 'DBnd2017')
	or die('Could not connect: '.mysql_error());

mysqli_select_db($link, 'dlewis12') or die ('Could not connect to database');

//inserting things to table
mysqli_select_db($link, 'dlewis12') or die ('Could not connect to database');

$stmt = $link->prepare("insert into user_age (age) values (?)"); //'?' is place holder, stmt is statement

$agevar = $_GET['age'];

$stmt->bind_param("i", $agevar); //i is integer

$stmt->execute();

//done with ^^^

//Perform SQL query
$query = 'select * from user_age;'; //need both semi colons, one for mySQL, one for PHP
$result = mysqli_query($link, $query) or die ('Query Failed' . mysql_error());//link to database, want to do a query

//Print out the results
?>
<table>
<?php
while($tuple = mysqli_fetch_array($result, MYSQL_ASSOC)){//get iterator of tuples from result set
	echo "\t<tr>\n"; //same as (question mark > < question mark-php)
	foreach($tuple as $col_val){
		echo "\t\t<td>$col_val</td>\n"; //yeah.. no idea what that <--- means
	}
	echo "\t<tr>\n";
}
?>
</table>
<?php
mysqli_free_result($result); //need at end of each page to free memory
mysqli_close($link); //and this
?>
</body>
</html>
