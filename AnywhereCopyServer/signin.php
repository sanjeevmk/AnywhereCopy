<?php
	require('PasswordHash.php');
	$hasher = new PasswordHash(8,TRUE);

	date_default_timezone_set('Asia/Kolkata');
	$message = " :: Signin :: Connecting to DB...\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
    /*connect to the database*/
    $db = mysqli_connect("aaop7r806gpouv.cevkojdfbu7r.us-west-2.rds.amazonaws.com","sanjeevmk4890","rafaelnadal","userdb");
        

    if(mysqli_connect_errno($db)){
          echo "Failed to connect to mysql  " . mysqli_connect_error();
          die("Connection to DB failed");
    }

	$message = " :: Signin :: Connected to DB.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	$uname = $_POST['uname'];
	$pass = $_POST['passwd'];

	$checkQuery = "SELECT username,password,mainkey from UserInfo WHERE username='$uname'";
    $result = mysqli_query($db,$checkQuery);
	
	$row = mysqli_fetch_array($result);

	$storedHash = $row['password'];
	$checked = $hasher->CheckPassword($pass,$storedHash);
	
	if(mysqli_num_rows($result) === 0){
		
		$message = " :: Signin :: User doesn't exist.\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
		echo "Fail";
		exit();
	}

	if(!$checked){
		//password doesn't match or user doesn't exist
		
		$message = " :: Signin :: Incorrect password.\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
		
		echo "Fail";
		exit();
	}

	$message = " :: Signin :: Successfully signed in.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	echo "Success";
	fclose($logfile);
	exit();
?>
