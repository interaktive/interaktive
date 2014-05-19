<html>
<link href="css/esteticaGeneral.css" rel="stylesheet" type="text/css">
<body>
<div id="contenedorPrincipal">
<div id="superior">
<span id="encabezado">Interaktive</span>
<hr>
<span id="titulo"> Cargar nuevo modelo: </span>

            <form action="upload_file.php" method="post"
            enctype="multipart/form-data">
            <label for="file">Archivo:</label>
            <input type="file" name="file" id="file">
            <input type="submit" name="submit" value="Cargar Archivo">
            </form>
<span id="titulo"> Lista de modelos disponibles: </span>

<?php 

include 'listarArchivos.php';
leer_archivos_y_directorios('upload/json/');

?>
</div>
<div id="inferior">
	<span>&copy; Copyright | Interaktive 2014 | Abraham Mendoza,Daniel Fernandez, Javier Trevi&ntilde;o</span>

</div>
</div>


</body>
</html>