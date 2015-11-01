<html>
<head>
<title>Sections</title>
</head>
<body>
<p> Here are the Sections: </p>
<?php
// Connecting, selecting database
$link = mysqli_connect('localhost', 'cjbara', 'database')
 or die('Could not connect: ' . mysql_error());
mysqli_select_db($link, 'domerdoors') or die('Could not select database');
// Performing SQL query
$query = 'SELECT * FROM Section';
$result = mysqli_query($link, $query) or die('Query failed: ' . mysql_error());
// Printing results in HTML
echo "<table>\n";
echo "\t<tr>\n
	\t\t<th>Dorm</th>\n
	\t\t<th>Section</th>\n
	\t\t<th>Floor</th>\n
	\t</tr>\n";
while ($tuple = mysqli_fetch_array($result, MYSQL_ASSOC)) {
 echo "\t<tr>\n";
 foreach ($tuple as $col_value) {
 echo "\t\t<td>$col_value</td>\n";
 }
 echo "\t</tr>\n";
}
echo "</table>\n";
// Free resultset
mysqli_free_result($result);
// Closing connection
mysqli_close($link);
?>
</body>
</html>
