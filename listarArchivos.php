<?php
 
function leer_archivos_y_directorios()
{
    //ruta de los archivos
    $ruta='upload/json/';
    // comprobamos si lo que nos pasan es un direcotrio
    if (is_dir($ruta))
    {
        // Abrimos el directorio y comprobamos que
        if ($aux = opendir($ruta))
        {
            while (($archivo = readdir($aux)) !== false)
            {
                // Si quisieramos mostrar todo el contenido del directorio pondríamos lo siguiente:
                // echo '<br />' . $file . '<br />';
                // Pero como lo que queremos es mostrar todos los archivos excepto "." y ".."
                if ($archivo[0]!="." && $archivo!="..")
                {
                    $ruta_completa = $ruta . '/' . $archivo;
 
                    // Comprobamos si la ruta más file es un directorio (es decir, que file es
                    // un directorio), y si lo es, decimos que es un directorio y volvemos a
                    // llamar a la función de manera recursiva.
                    if (is_dir($ruta_completa))
                    {
                        //echo "<br /><strong>Directorio:</strong> " . $ruta_completa;
                        leer_archivos_y_directorios($ruta_completa . "/");
                    }
                    else
                    {
                        //inicamos el form que listara los archivos
                        echo "<form name='input' action='mostrarModelo.php' method='get'>
                        <label><input type='radio' name='modelo' value='$archivo'>$archivo</label><br>";
                        //echo '' . $archivo . '<br />';
                    }
                }
            }
            //terminamos el form
            echo "<input type='submit' value='Usar Modelo'>
                        </form>";
            closedir($aux);
 
            // Tiene que ser ruta y no ruta_completa por la recursividad
           // echo "<strong>Fin Directorio:</strong>" . $ruta . "<br /><br />";
        }
    }
    else
    {
        echo $ruta;
        echo "<br />No es ruta valida";
    }
}