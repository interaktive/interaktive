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
         target="_blank">Interaktive JS</a> - Demo
         <br/>
         <?php 
				//extraemos el archivo seleccionado
				//echo '<p>hola </p>';
				
				$modeloUsado = $_GET['modelo']; 
				echo "<p>".$modeloUsado."</p>";
				$rutaModelo="upload/obj/".$modeloUsado;

			?>
         <a href="index.php">Menu archivos </a>
		</div>

    <div id="log">
    </div>

		<script src="build/three.min.js"></script>
		<script src="js/loaders/OBJLoader.js"></script>
		<script src="js/Detector.js"></script>
		<script src="js/libs/stats.min.js"></script>
		<!--<script src="js/demo.js"></script>-->
		<script type="text/javascript">

var dirY = 0, dirZ = 0, orbitY = 0;
var deltaDirY = 0, deltaDirZ = 0;
var maxDelta = 100;
var frameStep = 0.05;
var frameDistance = 30;
var statsEnabled=true;
var container, stats, loader;
var directionalLight, ambientLight;
var camera, scene, renderer;
var windowHalfX = window.innerWidth / 2;
var windowHalfY = window.innerHeight / 2;

$(document).ready(function() {
	var ctl = new Leap.Controller({enableGestures: true});
	var swiper = ctl.gesture('swipe');
	var tolerance = 75, cooldown = 200;

  var updateDirs = _.debounce(function(auxOrbitY, auxDirY, auxDirZ) {
    deltaDirY = 0, deltaDirZ = 0;
    orbitY = auxOrbitY, dirY = auxDirY, dirZ = auxDirZ;
  }, cooldown);

	swiper.update(function(g) {
    var auxOrbitY = 0, auxDirY = 0, auxDirZ = 0;

		if (Math.abs(g.translation()[0]) > tolerance) {
      auxOrbitY = g.translation()[0] > 0 ? -1 : 1
    } else if (Math.abs(g.translation()[1]) > tolerance) {
      auxDirY = g.translation()[1] > 0 ? 1 : -1
    } else if (Math.abs(g.translation()[2]) > tolerance) {
      auxDirZ = g.translation()[2] > 0 ? -1 : 1
    }

    updateDirs(auxOrbitY, auxDirY, auxDirZ);
	});

	ctl.connect();
})

// Rotar objeto izq/der (sobre su eje Y)
function rotate() {
  if (orbitY != 0) {
    object.rotation.y += (frameStep * orbitY);
  }
}

// Acercar/alejar la cámara (mover posición Z)
function scale() {
  if (((dirZ == 1 && camera.position.z < 330) ||
      (dirZ == -1 && camera.position.z > 100)) && deltaDirZ < maxDelta) {
    step = frameStep * 100 * dirZ;
    camera.position.z += step;
    deltaDirZ += Math.abs(step);
  }
}

// Trasladar el objeto arriba/abajo (mover posición Y)
function translate() {
  if (((dirY == 1 && object.position.y < 100) ||
      (dirY == -1 && object.position.y > -200)) && deltaDirY < maxDelta) {
    step = frameStep * 100 * dirY;
    object.position.y += step
    deltaDirY += Math.abs(step);
  }
}

init();
animate();

function init() {
	container = document.createElement('div');
	document.body.appendChild(container);

	camera =
    new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 1, 2000);

	camera.position.z = 300;

	scene = new THREE.Scene();

  ambientLight = new THREE.AmbientLight(0x221100);
  scene.add(ambientLight);

  directionalLight = new THREE.DirectionalLight(0xffeedd);
  directionalLight.position.set(0, 0, 1);
  scene.add(directionalLight);

  directionalLight=new THREE.DirectionalLight(0xffffff);
  directionalLight.position.x=1;
  directionalLight.position.y=1;
  directionalLight.position.z=-1;
  directionalLight.position.normalize();
  scene.add(directionalLight);

	var manager = new THREE.LoadingManager();
	manager.onProgress = function(item, loaded, total) {
    log('Loaded', item, loaded + '/' + total);
  };

	var texture = new THREE.Texture();

	var loader = new THREE.ImageLoader(manager);
	loader.load('upload/textures/UV_Grid_Sm.jpg', function(image) {
		texture.image = image;
		texture.needsUpdate = true;
	});


//cargamos el nuevo modelo

//-------------------

	var jsvar  = '<?php echo $rutaModelo;?>';//"upload/obj/male02.obj"//'<?php echo $rutaModelo; ?>';
	
	var loader = new THREE.OBJLoader(manager);
	loader.load(jsvar, function(object) {
		object.traverse(function(child) {
			if (child instanceof THREE.Mesh) {
				child.material.map = texture;
			}
		});
		object.position.x = 0;
		object.position.y = -80;
		object.position.z = 0;

		scene.add(object);
    window.object = object
	});

	//este codigo es para cargar los archivos JSON
/**
				var loader = new THREE.JSONLoader();
				//se crea la textura del objeto y los numeros es la posicion del objeto
				//IMPORTANTE EL OBJETO TIENE QUE SER DIBUJADO EN LOS PUNTOS 0,0,0
				//DESDE QUE SE CREA EL OBJ
				var callbackMale = function ( geometry, materials ) { createScene( geometry, materials, 0, -80, 0, 0 ) };
				var callbackFemale = function ( geometry, materials ) { createScene( geometry, materials, -80, FLOOR, 50, 0 ) };
				
				//pasamos la variable de php a js
				 var jsvar  = '<?php echo $rutaModelo; ?>';

				//se carga objeto con su textura
				loader.load( jsvar, callbackMale );
				*/

				
//-------------------

		

	renderer = new THREE.WebGLRenderer();
	renderer.setSize(window.innerWidth, window.innerHeight);
	container.appendChild(renderer.domElement);

  stats=new Stats();
  stats.domElement.style.position='absolute';
  stats.domElement.style.top='0px';
  stats.domElement.style.zIndex=100;
  container.appendChild(stats.domElement);

	window.addEventListener('resize', onWindowResize, false);
	document.addEventListener('keydown', onKeyDown, false);
}

function onKeyDown(e) {
  deltaDirY = 0, deltaDirZ = 0;
  dirY = 0, dirZ = 0, orbitY = 0;

	switch (e.keyCode) {
	case 37: // Left
    orbitY = -1;
		break;
	case 38: // Up
    dirY = -1;
		break;
	case 39: // Right
    orbitY = 1;
		break;
	case 40: // Down
    dirY = 1;
		break;
  case 81: // Q
    dirZ = 1;
    break;
  case 87: // W
    dirZ = -1;
    break;
	}
}

function onWindowResize() {
	windowHalfX = window.innerWidth / 2;
	windowHalfY = window.innerHeight / 2;

	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();

	renderer.setSize(window.innerWidth, window.innerHeight);
}

function animate() {
	requestAnimationFrame(animate);
	render();
  if (statsEnabled) stats.update();
}

function render() {
	camera.lookAt(scene.position);

  rotate();
  scale();
  translate();

	renderer.render(scene, camera);
}

function log() {
  var e = document.getElementById('log');
  text = Array.prototype.join.call(arguments, ' ');
  e.innerHTML = text + '<br/>' + e.innerHTML;
}

		</script>
	</body>
</html>
