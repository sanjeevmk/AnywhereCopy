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
		$message = " :: Copy :: Received copy.\n";
		file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	}
	
	$findkey = "SELECT mainkey from UserInfo WHERE username='$userid'";
	
	$result = mysqli_query($db,$findkey);

	$row = mysqli_fetch_array($result);

	$encryption_key = $row['mainkey'];
	$encCopied = mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$encryption_key,$copied,MCRYPT_MODE_CFB);

	$copystuff = "UPDATE UserInfo set copied='$encCopied' where username='$userid'";
	
	$result = mysqli_query($db,$copystuff);
	
	$message = " :: Copy :: Copy complete.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	fclose($logfile);
	exit();
?>
