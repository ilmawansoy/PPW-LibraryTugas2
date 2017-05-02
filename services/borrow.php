<?php
session_start();
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

	$conn = connectDB();

	$book_id = $_GET['id'];
	$user_id = $_SESSION['login_session']['user_id'];
	$quantity = $_GET['qnt'] - 1;
	$cek = false;

	$loan = "SELECT book_id, user_id FROM loan";
	$result = $conn->query($loan);
	$sql = "INSERT into loan (book_id, user_id) values('$book_id','$user_id')";
	$update = "UPDATE book SET quantity='$quantity' WHERE book_id=$book_id";
	
	while ($row = $result->fetch_assoc()) {
		if ($row['book_id'] == $book_id && $row['user_id'] == $user_id) {
			$cek = true;
		} 
	}

	if ($cek) {
		$_SESSION['status_pinjam'] = "You already borrowed this book.";
		if ($_GET['page'] == 'home') {
			header("Location: ../home.php");
		} else {
			echo '<input type="hidden" id="review-bookid" name="bookid" value="'.$_SESSION['book_id'].'">';
			header("Location: ../detail.php?id=" . $book_id);
		}
	} else {
		$sql = "INSERT into loan (book_id, user_id) values('$book_id','$user_id')";
		$update = "UPDATE book SET quantity='$quantity' WHERE book_id=$book_id";

		if($result = mysqli_query($conn, $sql) && $upResult = mysqli_query($conn, $update)) {
			$_SESSION['status_pinjam'] = "The book is ready to read!";
			if ($_GET['page'] == 'home') {
				header("Location: ../home.php#book-list");
			} else {
				echo '<input type="hidden" id="review-bookid" name="bookid" value="'.$_SESSION['book_id'].'">';
				header("Location: ../detail.php?id=" . $book_id);
			}
		} else {
			die("Error: $sql");
		}
		mysqli_close($conn);
	}
?>