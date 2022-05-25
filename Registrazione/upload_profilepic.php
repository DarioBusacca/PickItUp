<?php
    if (!(isset($_POST['upload-btn']))) {
    header("Location: /");
	}
	else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=postgres") 
                or die('Could not connect: ' . pg_last_error());
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Upload Profile Picture</title>

</head>
<body>
	<?php 
	if($dbconn){

		$username=$_GET['username'];
		mkdir("../Media/$username/");
		$target_dir = "../Media/$username/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Check if image file is a actual image or fake image
		if(isset($_POST["upload-btn"])) {
  			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  			if($check !== false) {
    			echo "File is an image - " . $check["mime"] . ".";
    			$uploadOk = 1;
  			} else {
    			echo "File is not an image.";
    			$uploadOk = 0;
  			}
		}

		// Check if file already exists
		if (file_exists($target_file)) {
  			echo "Sorry, file already exists.";
  			$uploadOk = 0;
		}

		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
  			echo "Sorry, your file is too large.";
  			$uploadOk = 0;
		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
  			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
 	 		echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
  			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    			echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  			} else {
    		echo "Sorry, there was an error uploading your file.";
  			}
		}

		if($uploadOk){
			$q1="update user_profile set picture=$1 where username=$2";
			$data= pg_query_params($dbconn,$q1,array($target_file,$username));
			if($data){
				header('Location:../index.php?username='. $username);

			}
		}
	}
?>

</body>
</html>