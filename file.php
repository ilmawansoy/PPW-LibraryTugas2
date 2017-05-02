<?php 
	if (! $_POST) {echo "400 Bad Request"; die();}
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "personal_library";
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	// Check connection
	if (!$conn) {
		die("Connection failed: " + mysqli_connect_error());
	}
	return $conn;

?>