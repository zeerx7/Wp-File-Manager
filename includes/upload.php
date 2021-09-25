<?php
$output_dir = $_GET['upload_dir'];
$relativepath = $_GET['relativepath'];

if(isset($_FILES["myfile"])) {
	$ret = array();	
	$error =$_FILES["myfile"]["error"];
	if($relativepath != '') {
		$relativepathparts = explode("/", $relativepath);
		array_pop($relativepathparts);
		echo exec('mkdir -p "' . $output_dir . '/' . implode('/', $relativepathparts) .'"');
		if(!is_array($_FILES["myfile"]["name"])) {
			$fileName = $_FILES["myfile"]["name"];
		   	move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$relativepath);
		  	$ret[]= $output_dir.$relativepath;
	  } else {
		$fileCount = count($_FILES["myfile"]["name"]);
		for($i=0; $i < $fileCount; $i++) {
			$fileName = $_FILES["myfile"]["name"][$i];
		 	 move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$relativepath);
			$ret[]= $output_dir.$relativepath;
		}
	  
	  }
	} else {
		if(!is_array($_FILES["myfile"]["name"])) {
			$fileName = $_FILES["myfile"]["name"];
			move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
			$ret[]= $fileName;
		} else {
		$fileCount = count($_FILES["myfile"]["name"]);
		for($i=0; $i < $fileCount; $i++) {
			$fileName = $_FILES["myfile"]["name"][$i];
			move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
			$ret[]= $fileName;
		}
		
		}
	}
    echo json_encode($ret);
 }
 ?>