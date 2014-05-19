<!DOCTYPE html>
<html lang="en">
  <head>
    <title>InteraKtive</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link href="css/esteticaGeneral.css" rel="stylesheet" type="text/css">
    <style>
    </style>
  </head>
<body>
  <div id="contenedorPrincipal">
    <div id="superior">
      <h1 id="encabezado">InteraKtive</h1>
      <hr>
      <h2 id="titulo">Cargar nuevo modelo:</h2>
        <form action="upload_file.php" method="post" enctype="multipart/form-data">
          <div>
            <label for="file">Archivo:</label>
            <input type="file" name="file" id="file">
          </div>
          <input type="submit" name="submit" value="Cargar Archivo">
        </form>
      <h2 id="titulo"> Lista de modelos disponibles: </h2>
      <?php 
        include 'listarArchivos.php';
        leer_archivos_y_directorios('upload/json/');
      ?>
    </div>
  </div>
  <div id="inferior">
    <small>&copy; Copyright InteraKtive 2014 | Abraham Mendoza, Daniel Fernandez, Javier Trevi&ntilde;o</small>
  </div>
</body>
</html>
