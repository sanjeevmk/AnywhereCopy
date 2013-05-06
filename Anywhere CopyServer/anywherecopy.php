<?php

	date_default_timezone_set('Asia/Kolkata');
	$message = " :: Copy :: Connecting to DB...\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	$db = mysqli_connect("aaop7r806gpouv.cevkojdfbu7r.us-west-2.rds.amazonaws.com","sanjeevmk4890","rafaelnadal","userdb");
	
	if(mysqli_connect_errno($db)){
		echo "Failed to connect to mysql  " . mysqli_connect_error();
		die("Connection to DB failed");
	}

	$message = " :: Copy :: Connected to DB.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	$userid = $_POST['userId'];
	$copied = $_POST['selected'];

	if($userid!='' && $copied!=''){
		$message = " :: Copy :: Received copy " . $copied . "from " . $userid . "\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	}
	
	$findkey = "SELECT mainkey from UserInfo WHERE username=?";
	
	if($stmt = mysqli_prepare($db,$findkey)){
		mysqli_stmt_bind_param($stmt,"s",$userid);
		if(!mysqli_stmt_execute($stmt)){
			$message = " :: Copy Mainkey :: Copy failed. \n";
			file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
		}
		mysqli_stmt_bind_result($stmt,$encryption_key);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	}
	
	$encCopied = mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$encryption_key,$copied,MCRYPT_MODE_CFB);
	
	$copystuff = "UPDATE UserInfo set copied=? WHERE username=?";
	
	if($stmt = mysqli_prepare($db,$copystuff)){
		mysqli_stmt_bind_param($stmt,"ss",$encCopied,$userid);
		if(!mysqli_stmt_execute($stmt)){
			$message = " :: Copy Main :: Copy failed. \n";
			file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
		}
		mysqli_stmt_bind_result($stmt,$result);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	}
	
	$message = " :: Copy :: Copy complete.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	mysqli_close($db);
	fclose($logfile);
	exit();
?>
