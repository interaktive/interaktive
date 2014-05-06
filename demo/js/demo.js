var dirY = 0, dirZ = 0, orbitY = 0;
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
	var tolerance = 75;

	swiper.update(function(g) {
    orbitY = 0, dirY = 0, dirZ = 0;

		if (Math.abs(g.translation()[0]) > tolerance) {
      orbitY = g.translation()[0] > 0 ? -1 : 1
    } else if (Math.abs(g.translation()[1]) > tolerance) {
      dirY = g.translation()[1] > 0 ? 1 : -1
    } else if (Math.abs(g.translation()[2]) > tolerance) {
      dirZ = g.translation()[2] > 0 ? -1 : 1
    }
	});

  swiper.stop(function() {
    console.log('stop');
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
  if ((dirZ == 1 && camera.position.z < 330) ||
      (dirZ == -1 && camera.position.z > 100)) {
    camera.position.z += (frameStep * 100 * dirZ);
  }
}

// Trasladar el objeto arriba/abajo (mover posición Y)
function translate() {
  if ((dirY == 1 && object.position.y < 100) ||
      (dirY == -1 && object.position.y > -200)) {
    object.position.y += (frameStep * 50 * dirY);
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

  ambientLight = new THREE.AmbientLight(0x101030);
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
	loader.load('textures/UV_Grid_Sm.jpg', function(image) {
		texture.image = image;
		texture.needsUpdate = true;
	});

	var loader = new THREE.OBJLoader(manager);
	loader.load('obj/male02/male02.obj', function(object) {
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
