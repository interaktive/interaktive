<?php
/*
if ($_FILES["file"]["error"] > 0) {
  echo "Error: " . $_FILES["file"]["error"] . "<br>";
} else {
  echo "Upload: " . $_FILES["file"]["name"] . "<br>";
  echo "Type: " . $_FILES["file"]["type"] . "<br>";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
  echo "Stored in: " . $_FILES["file"]["tmp_name"];
}*/
?>


<?php
/*
//maximo tamaño para archivo en kB
$maxKB=10000*800;
$allowedExts = array("obj");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
//restricciones
if ((($_FILES["file"]["type"] == "application/octet-stream")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < $maxKB)
&& in_array($extension, $allowedExts)) {
  if ($_FILES["file"]["error"] > 0) {
    echo "Error: " . $_FILES["file"]["error"] . "<br>";
  } else {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Stored in: " . $_FILES["file"]["tmp_name"];
  }
} else {
  echo "Archivo Invalido";
}*/
?>

<?php
//define ('SITE_ROOT', "/Applications/XAMPP/xamppfiles/htdocs/interaktive/usado/"("nuevosObjetos"(__FILE__)));

//maximo tamaño para archivo en kB
$maxKB=10000*800;
$allowedExts = array("obj");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
//separamos el nombre del archivo
$string = $_FILES["file"]["name"];
$tokenName = strtok($string, ".");


if ((($_FILES["file"]["type"] == "application/octet-stream"))
&& ($_FILES["file"]["size"] < $maxKB)
&& in_array($extension, $allowedExts)) {
  if ($_FILES["file"]["error"] > 0) {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
  } else {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
    if (file_exists("upload/obj/" . $_FILES["file"]["name"])) {
      echo $_FILES["file"]["name"] . " already exists. ";
    } else {
    	move_uploaded_file($_FILES['file']['tmp_name'], "upload/obj/" . $_FILES["file"]["name"]);
    	$command = escapeshellcmd('/usr/bin/python2.7 upload/convert_obj_three.py -i upload/obj/'.$tokenName.'.obj -o upload/json/'.$tokenName.'.js');
    	$output = shell_exec($command);
		echo "funciona: ".$output;
		echo "<br />";
      //move_uploaded_file($_FILES["file"]["tmp_name"],"nuevosObjetos/" . $_FILES["file"]["name"]);
      echo "Stored in: " . "upload/obj/" . $_FILES["file"]["name"];
      echo "<br/>";
      echo "nombre: ".$_FILES["file"]["name"];
      echo "nombre2: ".$tokenName;
      header('Location: menuArchivos.php');
    }
  }
} else {
  echo "Invalid file";
}




 


?>