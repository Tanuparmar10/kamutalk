<?php
define('DBUSER', 'nausad_john');
define('DBPASS', 'zmC;=%AeHYbP');
define('DBSERVER', 'localhost');
define('DBNAME', 'vision_exalt_sqldb');

/*
db : vision_exalt_sqldb
uname : nausad_john
upass : zmC;=%AeHYbP
*/

$conn = new mysqli(DBSERVER, DBUSER, DBPASS, DBNAME);
if ($conn->connect_error) {
	//die("Connection failed: " . $conn->connect_error);
} 
?>