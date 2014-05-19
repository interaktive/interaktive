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
	  <link href="css/esteticaGeneral.css" rel="stylesheet" type="text/css">	
	</head>

	<body>
		<div id="leap-feedback">
    </div>

		<div id="info">
			<small>&copy; Copyright Interaktive 2014 | Abraham Mendoza, Daniel Fernandez, Javier Trevi&ntilde;o</small>
		  <div id="encabezado2">
				<a href="index.php">Cambiar Modelo</a> |
        <?php 				
				  $modeloUsado = $_GET['modelo']; 
				  echo "Modelo: " . $modeloUsado;
				  $rutaModelo = "upload/json/" . $modeloUsado;
			  ?>
      </div>
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
