<?php 
	session_start();
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "personal_library";


	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
	     die("Connection failed: " . $conn->connect_error);
	} 

	if($_SERVER["REQUEST_METHOD"] == "POST"){

		$sql = "SELECT user_id, username, password, role FROM user";
		$result = $conn->query($sql);
		$logged = false;

		while ($row = $result->fetch_assoc()) {
			if(($_POST['username'] == $row['username'])&&($_POST['password'] == $row['password'])){
				$_SESSION['login_session'] = array('user'=>$row['username'],'role'=>$row['role'], 'user_id'=>$row['user_id']);
				$logged=true;
				unset($_SESSION['error_msg']);
				header("Location: ../index.php");
			}
		}
		if($logged == false){
			$_SESSION['error_msg']="Invalid Username or Password";
			header("Location: ../index.php");
		}
	}

	$conn->close();
?>