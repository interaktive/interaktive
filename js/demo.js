var dirY = 0, dirZ = 0, orbitY = 0;
var deltaDirY = 0, deltaDirZ = 0;
var maxDelta = 100;
var frameStep = 0.05;
var frameDistance = 30;
var container, loader;
var directionalLight, ambientLight;
var camera, scene, renderer;
var windowHalfX = window.innerWidth / 2;
var windowHalfY = window.innerHeight / 2;

$(document).ready(function() {
	var ctl = new Leap.Controller({enableGestures: true});
	var tolerance = 75, cooldown = 200;

  var updateDirs = _.debounce(function(auxOrbitY, auxDirY, auxDirZ) {
    deltaDirY = 0, deltaDirZ = 0;
    orbitY = auxOrbitY, dirY = auxDirY, dirZ = auxDirZ;
  }, cooldown);

  var showLeapFeedback = function() {
    $('#leap-feedback').addClass('active');
  }

  var hideLeapFeedback = function() {
    $('#leap-feedback').removeClass('active');
  }

	var swiper = ctl.gesture('swipe')
    .start(function() {
      showLeapFeedback();
    })
    .stop(function() {
      hieLeapFeedback();
    })
	  .update(function(g) {
      var auxOrbitY = 0, auxDirY = 0, auxDirZ = 0;

		  if (Math.abs(g.translation()[0]) > tolerance) {
        auxOrbitY = g.translation()[0] > 0 ? -1 : 1;
      } else if (Math.abs(g.translation()[1]) > tolerance) {
        auxDirY = g.translation()[1] > 0 ? -1 : 1;
      } else if (Math.abs(g.translation()[2]) > tolerance) {
        auxDirZ = g.translation()[2] > 0 ? -1 : 1;
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

	var loader = new THREE.JSONLoader();
	loader.load(modelName, function(geometry, materials) {
		object = new THREE.Mesh(geometry, new THREE.MeshFaceMaterial( materials ) );

		object.position.x = 0;
		object.position.y = -80;
		object.position.z = 0;

    object.scale.set(1, 1, 1);

		scene.add(object);
    window.object = object
	});

	renderer = new THREE.WebGLRenderer();
	renderer.setSize(window.innerWidth, window.innerHeight);
	container.appendChild(renderer.domElement);

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
}

function render() {
	camera.lookAt(scene.position);

  rotate();
  scale();
  translate();

	renderer.render(scene, camera);
}
