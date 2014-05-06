<html>
<body>
<h1> Cargar Nuevo Archivo </h1>

            <form action="upload_file.php" method="post"
            enctype="multipart/form-data">
            <label for="file">Filename:</label>
            <input type="file" name="file" id="file">
            <input type="submit" name="submit" value="Submit">
            </form>
<h1> Lista de archivos </h1>

<?php 

include 'listarArchivos.php';
leer_archivos_y_directorios('upload/json/');

?>



</body>
</html>