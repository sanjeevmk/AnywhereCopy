<?php
	date_default_timezone_set('Asia/Kolkata');
	$message = "DeleteKeys :: Connecting to DB...\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	/*connect to the database*/
	$db = mysqli_connect("aaop7r806gpouv.cevkojdfbu7r.us-west-2.rds.amazonaws.com","sanjeevmk4890","rafaelnadal","userdb");
	
	if(mysqli_connect_errno($db)){
		echo "Failed to connect to mysql  " . mysqli_connect_error();
		die("Connection to DB failed");
	}
	
	$message = "DeleteKeys :: Connected to DB.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	$deletequery = "DELETE FROM UserKeys";
	
	if(!mysqli_query($db,$deletequery)){
		exit();
	}
	
	$message = "DeleteKeys :: Deleted keys\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
?>