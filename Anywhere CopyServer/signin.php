<?php
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

	$checkQuery = "SELECT username from UserInfo WHERE username=?";
	if($stmt = mysqli_prepare($db,$checkQuery)){
		mysqli_stmt_bind_param($stmt,"s",$uname);
		if(!mysqli_stmt_execute(($stmt))){
			$message = " :: Signin User Fetch :: Failed " . mysqli_error($db) . "\n";
			file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
		}
		mysqli_stmt_store_result($stmt);
		
		if(mysqli_stmt_num_rows($stmt) === 0){
			$message = " :: Signin :: User doesn't exist.\n";
			file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
			echo "Fail";
			mysqli_stmt_close($stmt);
			exit();
		}
		
		mysqli_stmt_close($stmt);
	}
	
	$message = " :: Signin :: Successfully signed in.\n";
	file_put_contents("log.txt", date("m.d.Y H:i:s").$message, FILE_APPEND);
	
	mysqli_close($db);
		
	echo "Success";
	fclose($logfile);
	exit();
?>
