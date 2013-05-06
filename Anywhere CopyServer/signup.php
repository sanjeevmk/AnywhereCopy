<?php
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

	/*set encryption key*/
	$getidquery = "SELECT userkey from UserKeys LIMIT 1";
	if($stmt = mysqli_prepare($db,$getidquery)){
		if(!mysqli_stmt_execute($stmt)){
			$message = " :: Signup Key :: Signup failed. " . mysqli_error($db) . "\n";
			file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
			exit();	
		}
		mysqli_stmt_bind_result($stmt,$userid);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	}
	
	$encryption_key = openssl_random_pseudo_bytes(32);

	$registerQuery = "INSERT INTO UserInfo (username,mainkey) VALUES (?,?)";
	if($stmt = mysqli_prepare($db,$registerQuery)){
		mysqli_stmt_bind_param($stmt,"ss",$userid,$encryption_key);
		if(!mysqli_stmt_execute($stmt)){
			$message = " :: Signup Reister :: Signup failed. " . mysqli_error($db) . "\n";
			file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
			exit();
		}
		mysqli_stmt_close($stmt);
	}
	
	$makeuniquequery = "DELETE FROM UserKeys where userkey=?";
	if($stmt = mysqli_prepare($db,$makeuniquequery)){
		mysqli_stmt_bind_param($stmt,"s",$userid);
		if(!mysqli_stmt_execute($stmt)){
			$message = " :: Signup Unique:: Signup failed. " . mysqli_error($db) . "\n";
			file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
			exit();
		}
		mysqli_stmt_close($stmt);	
	}
	
	mysqli_close($db);
	
	$returndata = array();
	$returndata['status'] = 'success';
	$returndata['identity'] = $userid;
	
	echo json_encode($returndata);
	
	$message = " :: Registered successfully.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	exit();
  
?>