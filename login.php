<?php
session_start();
require_once('validacion_login/conexion.php');

// Evitar caché
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Validar sesión existente y redirigir
if (isset($_SESSION['usuario']['tipo_usuario'])) {
    $tipoUsuario = (int) $_SESSION['usuario']['tipo_usuario'];

    if ($tipoUsuario === 1) {
        header('Location: vista/empresa/inicio_empresa.php');
        exit;
    } elseif ($tipoUsuario === 2) {
        header('Location: /index.php');
        exit;
    }
}

// Configuración de logos
$logo_svg = "img/logo.svg";
$logo_png = "img/logo-32x32.png";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CJ INFORMATICA | Inicio</title>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?php echo $logo_svg; ?>">
  <link rel="alternate icon" type="image/png" href="<?php echo $logo_png; ?>">

  <!-- Estilos y librerías -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="css/estilos.css" rel="stylesheet">
  <link href="css/mensaje.css" rel="stylesheet">

<style>
body {
  background: radial-gradient(circle, #0f2027, #203a43, #2c5364);
  color: white;
}
header {
  background: linear-gradient(90deg, #1b2735, #2a5298);
  color: white;
  padding: 15px 0;
}
.card {
  border-radius: 1rem;
  background-color: rgba(255, 255, 255, 0.95);
}
.btn-primary {
  background: linear-gradient(45deg, #4e73df, #1cc88a);
  border: none;
  transition: all 0.3s ease;
}
.btn-primary:hover {
  background: linear-gradient(45deg, #1cc88a, #4e73df);
  transform: scale(1.03);
}
/* Ocultar loader correctamente */
#loader-wrapper.hidden {
    opacity: 0;
    visibility: hidden;
    transition: opacity .5s ease, visibility .5s ease;
}

/* Que el loader siempre cubra toda la pantalla hasta ocultarse */
#loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #0f2027;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    color: white;
}

</style>

</head>

<body>



<?php include('include/header.php'); ?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="col-lg-5 col-md-7">
    <div class="card shadow-lg border-0">
      <div class="card-body p-5">
        <div class="text-center mb-4">
          <i class="fas fa-laptop-code fa-3x text-primary mb-2"></i>
          <h4 class="text-primary">CJ INFORMATICA</h4>
          <p class="text-muted">Iniciá sesión en tu cuenta</p>
        </div>

        <!-- Formulario -->
        <form     id="formLg" method="post" action="#">
          <div class="form-group">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Tu usuario" maxlength="30" required>
          </div>

          <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Tu contraseña" maxlength="20" required>
          </div>

          <button type="submit" class="btn btn-primary btn-block mt-3">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
          </button>

          <div class="text-center mt-3">
            <a href="solicitar-cuenta.php" class="text-primary">¿No tenés cuenta? Solicitá una aquí</a>
          </div>
        </form>

        <!-- Mensaje de error -->
        <div id="errorLogin" class="alert-login mt-3" style="display:none;">
          <i class="fas fa-exclamation-triangle"></i> Usuario o contraseña incorrecta
        </div>

      </div>
    </div>
  </div>
</div>

<?php include('include/footer.php'); ?>

<!-- Scripts -->
<script src="./vendor/jquery/jquery.min.js"></script>
<script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="./js/sb-admin-2.min.js"></script>

    <script src="js/main.js"></script>

    <!-- TU SCRIPT PERSONAL -->
    <script src="js/script.js"></script>




    <!-- Partículas -->
    <script>
        const canvas = document.getElementById('animated-bg');
        const ctx = canvas.getContext('2d');

        let width, height;
        let particles = [];
        const mouse = {
            x: null,
            y: null,
            radius: 120 // Radio de influencia del mouse
        };

        // Ajustar tamaño del canvas
        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        // Detectar movimiento del mouse
        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        // Resetear posición del mouse cuando sale de la ventana
        window.addEventListener('mouseout', () => {
            mouse.x = null;
            mouse.y = null;
        });

        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.size = Math.random() * 2 + 1;
                this.baseX = this.x;
                this.baseY = this.y;
                this.speedX = (Math.random() - 0.5) * 1.5;
                this.speedY = (Math.random() - 0.5) * 1.5;
                this.density = Math.random() * 20 + 5; // Para efecto de repulsión
            }

            update() {
                this.x += this.speedX;
                this.y += this.speedY;

                // Rebote en bordes
                if (this.x < 0 || this.x > width) this.speedX *= -1;
                if (this.y < 0 || this.y > height) this.speedY *= -1;

                // Interacción con el mouse (repulsión/atracción)
                if (mouse.x && mouse.y) {
                    const dx = mouse.x - this.x;
                    const dy = mouse.y - this.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < mouse.radius) {
                        // Fuerza de repulsión
                        const forceDirectionX = dx / distance;
                        const forceDirectionY = dy / distance;
                        const maxDistance = mouse.radius;
                        const force = (maxDistance - distance) / maxDistance;

                        // Movimiento inverso (repulsión)
                        this.x -= forceDirectionX * force * this.density;
                        this.y -= forceDirectionY * force * this.density;
                    }
                }
            }

            draw() {
                const gradient = ctx.createRadialGradient(this.x, this.y, this.size / 2, this.x, this.y, this.size * 2);
                gradient.addColorStop(0, "rgba(78,115,223,1)");
                gradient.addColorStop(1, "rgba(78,115,223,0)");

                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = gradient;
                ctx.fill();
            }
        }

        // Inicializar partículas
        function init() {
            particles = [];
            for (let i = 0; i < 120; i++) {
                particles.push(new Particle());
            }
        }
        init();

        // Conectar partículas con líneas
        function connectParticles() {
            const maxDistance = 130;

            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < maxDistance) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(78,115,223, ${1 - distance / maxDistance})`;
                        ctx.lineWidth = 0.8;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                        ctx.closePath();
                    }
                }
            }
        }

        // Animación principal
        function animate() {
            ctx.clearRect(0, 0, width, height);
            particles.forEach(particle => {
                particle.update();
                particle.draw();
            });
            connectParticles();
            requestAnimationFrame(animate);
        }
        animate();
    </script>



   

<!-- Loader ocultar -->
<script>
  window.addEventListener('load', function() {
      setTimeout(() => {
          document.getElementById('loader-wrapper').classList.add('hidden');
      }, 2000);
  });
</script>

</body>
</html>
