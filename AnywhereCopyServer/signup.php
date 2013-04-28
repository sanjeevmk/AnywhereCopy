<?php
	require('PasswordHash.php');

	$hasher = new PasswordHash(8,TRUE);
	
	date_default_timezone_set('Asia/Kolkata');
	$message = " :: Connecting to DB...\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	/*connect to the database*/
	$db = mysqli_connect("aaop7r806gpouv.cevkojdfbu7r.us-west-2.rds.amazonaws.com","sanjeevmk4890","rafaelnadal","userdb");
	
	if(mysqli_connect_errno($db)){
		echo "Failed to connect to mysql  " . mysqli_connect_error();
		die("Connection to DB failed");
	}
	/*finished connection*/
	
	$message = " :: Connected to DB.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	/*get the form inputs*/
    $uname = $_POST['inputName'];
	$passwd = $_POST['inputPassword'];
	$cpasswd = $_POST['inputConfirm'];
	
	/*verify username is not blank*/
	if(trim($uname) === ''){
		
		$message = " :: Username blank.\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
        $returndata = array();
		$returndata['where'] = "uname";
        $returndata['message'] = "Enter user name";
		echo json_encode($returndata);
        exit();
	}
			
	/*verify password is confirmed*/
	if($passwd != $cpasswd){
		
		$message = " :: Password not confirmed.\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
		$returndata = array();
		$returndata['where'] = "confirm";
		$returndata['message'] = "Passwords don't match";
		echo json_encode($returndata);
		exit();
	}

	/*check if username already exists*/
	$checkUserQuery = "SELECT username from UserInfo WHERE username='$uname'";
	$result = mysqli_query($db,$checkUserQuery);

	if(mysqli_num_rows($result) > 0){

		$message = " :: User exists.\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
		
		$returndata = array();
		$returndata['where'] = "uname";
		$returndata['message'] = "This user name already exists";
		echo json_encode($returndata);
		exit();
	}


	/*unique username, register*/

	/*set encryption key*/
	$encryption_key = openssl_random_pseudo_bytes(32);

	/*hash the password, as we shouldn't decrypt this, just verification*/
	$pwhash = $hasher->HashPassword($passwd);

	$registerQuery = "INSERT INTO UserInfo (username,password,mainkey) VALUES ('$uname','$pwhash','$encryption_key')";
		
	if(!mysqli_query($db,$registerQuery)){
		die("Error inserting into DB " . mysqli_error());
	}
		
	mysqli_close($db);
	
	$returndata = array();
	$returndata['status'] = 'success';
	echo json_encode($returndata);
	
	$message = " :: Registered successfully.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	exit();
  
?>
