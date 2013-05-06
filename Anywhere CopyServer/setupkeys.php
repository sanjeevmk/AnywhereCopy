<?php
	date_default_timezone_set('Asia/Kolkata');
	$message = "SetupKeys :: Connecting to DB...\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	/*connect to the database*/
	$db = mysqli_connect("aaop7r806gpouv.cevkojdfbu7r.us-west-2.rds.amazonaws.com","sanjeevmk4890","rafaelnadal","userdb");
	
	if(mysqli_connect_errno($db)){
		echo "Failed to connect to mysql  " . mysqli_connect_error();
		die("Connection to DB failed");
	}
	
	$message = "SetupKeys :: Connected to DB.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	function uniqueandrandom($min,$max,$quantity){
		$numbers = range($min,$max);
		shuffle($numbers);
		return array_slice($numbers,0,$quantity);	
	}
	
	$createkeysquery = "create table UserKeys (userkey int)";
	
	if(!mysqli_query($db,$createkeysquery)){
		exit();
	}
	
	$message = "SetupKeys :: Created table.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	$key_array = uniqueandrandom(100000,200000,100000);
	
	foreach($key_array as &$value){
		if(!mysqli_query($db,"insert into UserKeys (userkey) values ($value)")){
			exit();
		} 
	}
	
	$message = "SetupKeys :: Inserted keys\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
?>