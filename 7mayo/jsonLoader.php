<!DOCTYPE html>
<html lang="en">
	<head>
		<title>three.js webgl - io - OBJ converter</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<style>
			body {
				background:#fff;
				padding:0;
				margin:0;
				overflow:hidden;
				font-family:georgia;
				text-align:center;
			}
			h1 { }
			a { color:skyblue }

			#info { position:absolute; top:0px; width: 100%; text-align:center; }
			.button { background:#000; color:#fff; padding:0.2em 0.5em; cursor:pointer }
			.inactive { background:#999; color:#eee }
		</style>
	</head>

	<body>
		
		<div id="container"></div>
		<div id="info">
			<h1>Interaktive</h1>
			<!--BOTON PARA CARGAR OBJETO-->
			<span id="cargarOBJ" class="button">Cargar Nuevo OBJ</span>
			<!--<span id="rcanvas" class="button inactive">2d canvas renderer</span>-->
			<span id="rwebgl" class="button">Objeto Cargado</span>
			
			<br/>
			<?php 
				//extraemos el archivo seleccionado
				//echo '<p>hola </p>';
				
				$modeloUsado = $_GET['modelo']; 
				echo "<p>".$modeloUsado."</p>";
				$rutaModelo="upload/json/".$modeloUsado;

			?>
			<a href="index.php">Menu archivos </a>
			
		
			
		</div>


		<script src="build/three.min.js"></script>
		<script src="js/loaders/OBJLoader.js"></script>
		<script src="js/Detector.js"></script>
		<script src="js/libs/stats.min.js"></script>
		<script src="js/Detector.js"></script>
		<script src="js/libs/stats.min.js"></script>
		<script src="js/demo.js"></script>

		<script>

			var SCREEN_WIDTH = window.innerWidth;
			var SCREEN_HEIGHT = window.innerHeight;
			var FLOOR = -250;

			var container, stats;

			var camera, scene;
			var canvasRenderer, webglRenderer;

			var mesh, zmesh, geometry;

			var mouseX = 0, mouseY = 0;

			var windowHalfX = window.innerWidth / 2;
			var windowHalfY = window.innerHeight / 2;

			var render_canvas = 1, render_gl = 1;
			var has_gl = 0;

			var bcanvas = document.getElementById( "rcanvas" );
			var bwebgl = document.getElementById( "rwebgl" );

			document.addEventListener( 'mousemove', onDocumentMouseMove, false );

			init();
			animate();

			render_canvas = !has_gl;
			bwebgl.style.display = has_gl ? "inline" : "none";
			bcanvas.className = render_canvas ? "button" : "button inactive";

			function init() {

				container = document.getElementById( 'container' );

				camera = new THREE.PerspectiveCamera( 75, SCREEN_WIDTH / SCREEN_HEIGHT, 1, 100000 );
				camera.position.z = 500;

				scene = new THREE.Scene();

				// GROUND
				/**
				var x = document.createElement( "canvas" );
				var xc = x.getContext("2d");
				x.width = x.height = 128;
				xc.fillStyle = "#fff";
				xc.fillRect(0, 0, 128, 128);
				xc.fillStyle = "#000";
				xc.fillRect(0, 0, 64, 64);
				xc.fillStyle = "#999";
				xc.fillRect(32, 32, 32, 32);
				xc.fillStyle = "#000";
				xc.fillRect(64, 64, 64, 64);
				xc.fillStyle = "#555";
				xc.fillRect(96, 96, 32, 32);

				var xm = new THREE.MeshBasicMaterial( { map: new THREE.Texture( x, new THREE.UVMapping(), THREE.RepeatWrapping, THREE.RepeatWrapping ) } );
				xm.map.needsUpdate = true;
				xm.map.repeat.set( 10, 10 );
				//dimensiones del piso
				geometry = new THREE.PlaneGeometry( 200, 200, 15, 10 );

				mesh = new THREE.Mesh( geometry, xm );
				mesh.position.set( 0, FLOOR, 0 );
				mesh.rotation.x = - Math.PI / 2;
				mesh.scale.set( 10, 10, 10 );
				scene.add( mesh );
				**/
				// SPHERES
				/**
				var material_spheres = new THREE.MeshLambertMaterial( { color: 0xdddddd } ),
					sphere = new THREE.SphereGeometry( 100, 16, 8 );
					
				for ( var i = 0; i < 10; i ++ ) {

					mesh = new THREE.Mesh( sphere, material_spheres );

					mesh.position.x = 500 * ( Math.random() - 0.5 );
					mesh.position.y = 300 * ( Math.random() - 0 ) + FLOOR;
					mesh.position.z = 100 * ( Math.random() - 1 );

					mesh.scale.x = mesh.scale.y = mesh.scale.z = 0.25 * ( Math.random() + 0.5 );

					scene.add( mesh );

				}
				**/

				// LIGHTS

				var ambient = new THREE.AmbientLight( 0x221100 );
				scene.add( ambient );

				var directionalLight = new THREE.DirectionalLight( 0xffeedd, 1.5 );
				directionalLight.position.set( 0, -70, 100 ).normalize();
				scene.add( directionalLight );

				// RENDERER

				if ( render_gl ) {

					try {

						webglRenderer = new THREE.WebGLRenderer();
						webglRenderer.setClearColor( 0xffffff );
						webglRenderer.setSize( SCREEN_WIDTH, SCREEN_HEIGHT );
						webglRenderer.domElement.style.position = "relative";

						container.appendChild( webglRenderer.domElement );

						has_gl = 1;

					}
					catch (e) {
					}

				}

				if ( render_canvas ) {

					canvasRenderer = new THREE.CanvasRenderer();
					canvasRenderer.setClearColor( 0xffffff );
					canvasRenderer.setSize( SCREEN_WIDTH, SCREEN_HEIGHT );
					container.appendChild( canvasRenderer.domElement );

				}

				// STATS

				stats = new Stats();
				stats.domElement.style.position = 'absolute';
				stats.domElement.style.top = '0px';
				stats.domElement.style.zIndex = 100;
				container.appendChild( stats.domElement );

				//

				//bcanvas.addEventListener("click", toggleCanvas, false);
				bwebgl.addEventListener("click", toggleWebGL, false);

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
				//loader.load( "obj/female02/Female02_slim.js", callbackFemale );

				//

				window.addEventListener( 'resize', onWindowResize, false );

			}

			function onWindowResize() {

				windowHalfX = window.innerWidth / 2;
				windowHalfY = window.innerHeight / 2;

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				if ( webglRenderer ) webglRenderer.setSize( window.innerWidth, window.innerHeight );
				if ( canvasRenderer ) canvasRenderer.setSize( window.innerWidth, window.innerHeight );

			}

			function createScene( geometry, materials, x, y, z, b ) {

				zmesh = new THREE.Mesh( geometry, new THREE.MeshFaceMaterial( materials ) );
				zmesh.position.set( x, y, z );
				zmesh.scale.set( 3, 3, 3 );
				scene.add( zmesh );
				//Se crea la paleta de materiales
				//createMaterialsPalette( materials, 100, b );

			}

			function createMaterialsPalette( materials, size, bottom ) {

				for ( var i = 0; i < materials.length; i ++ ) {

					// material

					mesh = new THREE.Mesh( new THREE.PlaneGeometry( size, size ), materials[i] );
					mesh.position.x = i * (size + 5) - ( ( materials.length - 1 )* ( size + 5 )/2);
					mesh.position.y = FLOOR + size/2 + bottom;
					mesh.position.z = -100;
					scene.add( mesh );

					// number

					var x = document.createElement( "canvas" );
					var xc = x.getContext( "2d" );
					x.width = x.height = 128;
					xc.shadowColor = "#000";
					xc.shadowBlur = 7;
					xc.fillStyle = "orange";
					xc.font = "50pt arial bold";
					xc.fillText( i, 10, 64 );

					var xm = new THREE.MeshBasicMaterial( { map: new THREE.Texture( x ), transparent: true } );
					xm.map.needsUpdate = true;

					mesh = new THREE.Mesh( new THREE.PlaneGeometry( size, size ), xm );
					mesh.position.x = i * ( size + 5 ) - ( ( materials.length - 1 )* ( size + 5 )/2);
					mesh.position.y = FLOOR + size/2 + bottom;
					mesh.position.z = -99;
					scene.add( mesh );

				}

			}
			//ACCIONES DEL MOUSE PARA LA CAMARA
			//AQUI DEBEMOS METER LAS COORDENADAS DEL LEAP MOTION
			function onDocumentMouseMove(event) {

				mouseX = ( event.clientX - windowHalfX );
				mouseY = ( event.clientY - windowHalfY );

			}

			//

			function animate() {

				requestAnimationFrame( animate );

				render();
				stats.update();

			}

			function render() {

				camera.position.x += ( mouseX - camera.position.x ) * .05;
				camera.position.y += ( - mouseY - camera.position.y ) * .05;

				camera.lookAt( scene.position );

				if ( render_gl && has_gl ) webglRenderer.render( scene, camera );
				if ( render_canvas ) canvasRenderer.render( scene, camera );

			}

			function toggleCanvas() {

				render_canvas = !render_canvas;
				bcanvas.className = render_canvas ? "button" : "button inactive";

				render_gl = !render_canvas;
				bwebgl.className = render_gl ? "button" : "button inactive";

				if( has_gl )
					webglRenderer.domElement.style.display = render_gl ? "block" : "none";

				canvasRenderer.domElement.style.display = render_canvas ? "block" : "none";

			}

			function toggleWebGL() {

				render_gl = !render_gl;
				bwebgl.className = render_gl ? "button" : "button inactive";

				render_canvas = !render_gl;
				bcanvas.className = render_canvas ? "button" : "button inactive";

				if( has_gl )
					webglRenderer.domElement.style.display = render_gl ? "block" : "none";

				canvasRenderer.domElement.style.display = render_canvas ? "block" : "none";

			}
		</script>-->

	</body>
</html>
