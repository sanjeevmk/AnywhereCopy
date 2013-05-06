<?php
	date_default_timezone_set('Asia/Kolkata');
	$message = " :: Paste :: Connecting to DB...\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	$db = mysqli_connect("aaop7r806gpouv.cevkojdfbu7r.us-west-2.rds.amazonaws.com","sanjeevmk4890","rafaelnadal","userdb");
	
	$message = " :: Copy :: Connected to DB.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	if(mysqli_connect_errno($db)){
		echo "Failed to connect to mysql  " . mysqli_connect_error();
		die("Connection to DB failed");
	}

	$userid = $_POST['userId'];
	
	if($userid!=''){
		$message = " :: Paste :: Received paste from " . $userid . "\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	}
	
	$findkey = "SELECT mainkey,copied from UserInfo WHERE username=?";
	if($stmt = mysqli_prepare($db,$findkey)){
		$message = " :: Paste :: Prepared\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);

		mysqli_stmt_bind_param($stmt,"s",$userid);
		$message = " :: Paste :: Binded " . $userid . "\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
		if(!mysqli_stmt_execute($stmt)){
			$message = " :: Paste Fetch :: Failed.\n";
			file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
			exit();
		}
		
		$message = " :: Paste :: Executed\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
			
		mysqli_stmt_bind_result($stmt,$decryption_key,$encCopied);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	}
	
	
	$decrypted_copied = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $decryption_key,$encCopied,MCRYPT_MODE_CFB);
	
	if($decrypted_copied){
		$message = " :: Paste :: Decrypted.\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	}
	
	
	echo $decrypted_copied;
	
	$message = " :: Paste :: Paste fetched, decrypted, sent\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	mysqli_close($db);
	fclose($logfile);
	exit();
?>