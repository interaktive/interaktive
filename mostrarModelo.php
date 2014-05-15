<!DOCTYPE html>
<html lang="en">
	<head>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
  	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="http://js.leapmotion.com/0.2.2/leap.min.js"></script>

		<title>InteraKtive Demo</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/demo.css" />
		
		
	</head>

	<body>
		<div id="info">
		  <a href="https://github.com/interaktive/interaktive"
         target="_blank">Interaktive JS</a> 
         
         <?php 
				//extraemos el archivo seleccionado
				//echo '<p>hola </p>';
				
				$modeloUsado = $_GET['modelo']; 
				echo "-".$modeloUsado."-";
				$rutaModelo="upload/json/".$modeloUsado;

			?>
         
         <a href="index.php">Menu archivos </a>
		</div>

    <div id="log">
    </div>

		<script src="build/three.min.js"></script>
		<script src="js/loaders/OBJLoader.js"></script>
		<script src="js/Detector.js"></script>
		<script src="js/libs/stats.min.js"></script>
		<script type="text/javascript">
		  var modelName  = '<?php echo $rutaModelo;?>';
		</script>
		<script src="js/demo.js"></script>
	</body>
</html>
