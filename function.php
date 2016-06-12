<?php
	
	ini_set("display_errors", 1);
	$vead=array();
	$failidearv=0;
	function connect_db(){
  		global $connection;
 		 $host="localhost";
 		 $user="test";
 		 $pass="t3st3r123";
 		 $db="test";
 		 $connection = mysqli_connect($host, $user, $pass, $db) or die("ei saa mootoriga ühendust");
 		 mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse - ".mysqli_error($connection));
	}
	
	function avaleht(){
		global $connection;
		$sql="SELECT * FROM srekkaro__eksam";
		$sql=mysqli_real_escape_string($connection, $sql);
		$result=mysqli_query($connection, $sql);
		$failidearv=mysqli_num_rows($result);
	}

	
	
	function laadipilt(){
		global $connection;
		global $failidearv;
			$pildiurl="";
			if ($_SERVER['REQUEST_METHOD']=='GET'){
				include_once('uploadform.html');
			}
			if ($_SERVER['REQUEST_METHOD']=='POST'){
				if (empty($_POST["pealkiri"])){
					$vead[]= "Pildil puudub pealkiri!";
				}
				if (!empty($_FILES['pilt']['name'])){
					$pildiurl=uploadPilt('pilt');
				} 
				if ($pildiurl==""){
					$vead[]="Puudub pildifail!";
				}
				$pealkiri=mysqli_real_escape_string($connection, $_POST["pealkiri"]);
				$pildiurl=mysqli_real_escape_string($connection, $pildiurl);	
				if (empty($vead)){
					$sql= "INSERT INTO srekkaro__eksam ( pealkiri, pilt) VALUES ('$pealkiri', '$pildiurl')";
					$tulemus=mysqli_query($connection, $sql);
					$viga= mysqli_error($connection);
					print_r($viga);	
						if ($tulemus){
							if(mysqli_affected_rows($connection)>0){
								header("Location: ?leht=avaleht");
							}
						}
					}		
			}
		include_once('uploadform.html');	
	}
	
	function uploadPilt($name){
	global $failidearv;
	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$allowedTypes = array("image/gif", "image/jpeg", "image/png","image/pjpeg");
	$ajutine = explode(".", $_FILES[$name]["name"]);
	$extension = end($ajutine);

	if ( in_array($_FILES[$name]["type"], $allowedTypes)
		&& ($_FILES[$name]["size"] < 600000)
		&& in_array($extension, $allowedExts)) {
    // fail õiget tüüpi ja suurusega
		if ($_FILES[$name]["error"] > 0) {
			$_SESSION['notices'][]= "Return Code: " . $_FILES[$name]["error"];
			return "";
		} else {
      // vigu ei ole
			if (file_exists("pildid/" . $_FILES[$name]["name"])) {
        // fail olemas ära uuesti lae, tagasta failinimi
				$_SESSION['notices'][]= $_FILES[$name]["name"] . " juba eksisteerib. ";
				return "pildid/" .$_FILES[$name]["name"];
			} else {
        // kõik ok, aseta pilt
				move_uploaded_file($_FILES[$name]["tmp_name"], "pildid/" . $_FILES[$name]["name"]);
				return "pildid/" .$_FILES[$name]["name"];
				$failidearv=$failidearv+1;
			}
		}
	} else {
		return "";
	}
}

function lopetaSessioon(){
		global $failidearv;
		$failidearv=0;
		$_SESSION=array();
		session_destroy();
		header("Location: ?");
	}
	
	function alustaSessioon(){
		
		session_start();
		header("Location: ?");
	}