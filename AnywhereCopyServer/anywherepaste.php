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
		$message = " :: Paste :: Received paste.\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	}
	
	$findkey = "SELECT mainkey,copied from UserInfo WHERE username='$userid'";
	$result = mysqli_query($db,$findkey);

	$row = mysqli_fetch_array($result);

	$decryption_key = $row['mainkey'];
	$encCopied = $row['copied'];
	 
	$decrypted_copied = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $decryption_key,$encCopied,MCRYPT_MODE_CFB);
	
	$message = " :: Paste :: Paste fetched, decrypted, sent\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	echo $decrypted_copied;
	
	fclose($logfile);
	exit();
?>
