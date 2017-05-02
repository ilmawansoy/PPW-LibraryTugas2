<?php
session_start();

$perintah=$_REQUEST["perintah"];

switch($perintah) {
	case "tambah" : $ret=tambah($_REQUEST['content'],$_REQUEST['bookid'],$_REQUEST['userid']);
					echo $ret;
					break;
	case "tampil" : $ret=tampil($_REQUEST['bookid']);
					echo $ret;
					break;
}

function connectDB() {
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "personal_library";
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed: " + mysqli_connect_error());
	}
	return $conn;
}

function tambah($content, $bookid, $userid) {
	$today = date("Y-m-d");
	$conn = connectDB();

	if($content!=""){
		$sql = "INSERT into review (content, book_id, user_id, date) values('$content','$bookid','$userid','$today')";
		
		if($result = mysqli_query($conn, $sql)) {
			return 'ok'; 
		}
	} else {
		return 'gagal';
	}
	mysqli_close($conn);
}

function tampil($bookid) {
	$conn = connectDB();
	$review = "SELECT review.user_id, review.date, review.content, user.username FROM review INNER JOIN user ON review.user_id=user.user_id WHERE book_id=$bookid ORDER BY review.review_id";
	$reviewResult = $conn->query($review);
	$print = "";

	while($row = $reviewResult->fetch_assoc()) {
		$print = $print . '<div><h4  class="user_review"><strong>' . $row['username'] . '</strong></h4>
		<p id="date" class="user_review">'. $row['date'] .'</p></div><p>'. $row['content'] .'</p><hr>';
	}
	return $print;
}
?>