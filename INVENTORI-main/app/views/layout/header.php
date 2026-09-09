<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? $data['title'] . ' - ' : '' ?><?= APP_NAME ?></title>
    <!-- Tailwind CSS (Stylesheet + Play CDN con fallback seguro) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      if (typeof tailwind !== 'undefined') {
        tailwind.config = {
          darkMode: 'class',
          theme: {
            extend: {
              colors: {
                glass: {
                  DEFAULT: 'rgba(255, 255, 255, 0.04)',
                  card: 'rgba(18, 20, 38, 0.55)',
                  border: 'rgba(255, 255, 255, 0.08)',
                  hover: 'rgba(255, 255, 255, 0.12)'
                },
                premium: {
                  deep: '#090a15',
                  dark: '#121426',
                  purple: '#6d28d9',
                  violet: '#8b5cf6',
                  emerald: '#10b981',
                  cyan: '#06b6d4',
                  rose: '#f43f5e'
                }
              },
              fontFamily: {
                sans: ['Outfit', 'Inter', 'sans-serif'],
              }
            }
          }
        };
      }
    </script>
    <!-- FontAwesome para Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/img/logo.png">
    <!-- Estilos personalizados con control de versión anti-caché -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css?v=<?= time() ?>">
    <script>
        // Variables globales para JS
        window.BASE_URL = "<?= BASE_URL ?>";
        window.SESSION_TIMEOUT = <?= defined('SESSION_TIMEOUT') ? SESSION_TIMEOUT : 900 ?>;
        window.IS_LOGGED_IN = <?= Session::isLoggedIn() ? 'true' : 'false' ?>;

        /* ---- Sistema de Selección Dinámica de Tema (Modo Oscuro / Modo Claro) ---- */
        function applyAppTheme(mode) {
            var root = document.documentElement;
            var icons = document.querySelectorAll('#global-theme-icon, #theme-icon');
            var btns  = document.querySelectorAll('#global-theme-toggle, #theme-toggle');

            if (mode === 'light') {
                root.classList.add('light');
                icons.forEach(function(icon) {
                    icon.className = 'fa-solid fa-sun text-base text-amber-400';
                });
                btns.forEach(function(btn) {
                    btn.title = 'Cambiar a Modo Oscuro';
                });
            } else {
                root.classList.remove('light');
                icons.forEach(function(icon) {
                    icon.className = 'fa-solid fa-gear text-base text-emerald-400';
                });
                btns.forEach(function(btn) {
                    btn.title = 'Cambiar a Modo Claro';
                });
            }
        }

        function toggleAppTheme(e) {
            var root = document.documentElement;
            var isLight = root.classList.contains('light');
            var newMode = isLight ? 'dark' : 'light';

            applyAppTheme(newMode);
            localStorage.setItem('app_theme', newMode);

            // 1. Animar rotación del botón al hacer clic
            var btns = document.querySelectorAll('#global-theme-toggle, #theme-toggle');
            btns.forEach(function(btn) {
                btn.style.transform = 'rotate(360deg) scale(1.25)';
                setTimeout(function() {
                    btn.style.transform = '';
                }, 500);
            });

            // 2. Disparar ráfaga de constelación desde la posición del clic
            var clickX = e ? e.clientX : window.innerWidth / 2;
            var clickY = e ? e.clientY : window.innerHeight / 2;
            if (typeof window.triggerConstellationPulse === 'function') {
                window.triggerConstellationPulse(clickX, clickY);
            }

            // 3. Notificación flotante (Toast)
            var toast = document.createElement('div');
            toast.className = 'fixed top-5 right-5 z-50 px-5 py-3 rounded-2xl bg-slate-900/95 border-2 border-emerald-400 text-emerald-300 shadow-2xl font-bold text-xs flex items-center space-x-2 backdrop-blur-xl animate-bounce';
            toast.innerHTML = (newMode === 'light')
                ? '<i class="fa-solid fa-sun text-amber-400 text-base"></i><span>¡Modo Claro Activado!</span>'
                : '<i class="fa-solid fa-moon text-emerald-400 text-base"></i><span>¡Modo Oscuro Activado!</span>';
            document.body.appendChild(toast);
            setTimeout(function() {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s ease';
                setTimeout(function() { toast.remove(); }, 500);
            }, 2000);
        }

        (function () {
            var savedTheme = localStorage.getItem('app_theme') || 'dark';
            if (savedTheme === 'light') {
                document.documentElement.classList.add('light');
            } else {
                document.documentElement.classList.remove('light');
            }
        })();

        document.addEventListener('DOMContentLoaded', function () {
            var savedTheme = localStorage.getItem('app_theme') || 'dark';
            applyAppTheme(savedTheme);
        });
    </script>
</head>
<body class="h-full flex flex-col bg-slate-950 relative">

<!-- Fondo Animado Dinámico con Red de Constelaciones Geométricas Interactivas + Figuras en Movimiento + Marca de Agua Parallax -->
<div class="animated-bg-wrapper pointer-events-none fixed inset-0 overflow-hidden z-0" aria-hidden="true">
    <div id="cursor-interactive-glow" class="cursor-glow-orb pointer-events-none"></div>
    <div class="bg-shape shape-blob-green-1 pointer-events-none"></div>
    <div class="bg-shape shape-blob-blue-1 pointer-events-none"></div>
    <div class="bg-shape shape-blob-green-2 pointer-events-none"></div>
    <div class="bg-shape shape-blob-blue-2 pointer-events-none"></div>

    <!-- Canvas de Red de Constelaciones Geométricas Elegantes e Interactivas -->
    <canvas id="geo-constellation-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0 opacity-90"></canvas>

    <!-- Marca de Agua Institucional Flotante Interactiva con el Ratón -->
    <div class="institutional-watermark-overlay pointer-events-none"></div>
</div>

<script>
    // 1. Red de Constelaciones Geométricas Interactivas con Reacción Física al Mouse
    (function initGeoConstellationCanvas() {
        var canvas = document.getElementById('geo-constellation-canvas');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        var width, height;
        var points = [];
        var shockwaves = [];
        var maxDist = 190;
        var mouse = { x: null, y: null };

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        var count = Math.min(95, Math.floor((width * height) / 14000));
        var colors = [
            'rgba(16, 185, 129, ',  // Verde Esmeralda Neón
            'rgba(6, 182, 212, ',   // Cyan Neón
            'rgba(217, 70, 239, ',  // Magenta / Púrpura Neón
            'rgba(30, 58, 138, '    // Azul Oscuro
        ];
        for (var i = 0; i < count; i++) {
            points.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 1.1,
                vy: (Math.random() - 0.5) * 1.1,
                radius: Math.random() * 2.5 + 2,
                color: colors[Math.floor(Math.random() * colors.length)]
            });
        }

        document.addEventListener('mousemove', function(e) {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        // Función global para ráfaga de pulso neón
        window.triggerConstellationPulse = function(originX, originY) {
            shockwaves.push({
                x: originX,
                y: originY,
                radius: 10,
                maxRadius: Math.max(width, height) * 0.7,
                speed: 18,
                alpha: 0.9
            });
            // Reacelerar partículas cercanas
            points.forEach(function(p) {
                var dx = p.x - originX;
                var dy = p.y - originY;
                var d = Math.sqrt(dx * dx + dy * dy);
                if (d < 300) {
                    p.vx += (dx / (d + 1)) * 4;
                    p.vy += (dy / (d + 1)) * 4;
                }
            });
        };

        function animate() {
            ctx.clearRect(0, 0, width, height);

            // Animar Shockwaves de Clic
            for (var w = shockwaves.length - 1; w >= 0; w--) {
                var wave = shockwaves[w];
                wave.radius += wave.speed;
                wave.alpha *= 0.96;
                ctx.beginPath();
                ctx.arc(wave.x, wave.y, wave.radius, 0, Math.PI * 2);
                ctx.strokeStyle = 'rgba(16, 185, 129, ' + wave.alpha + ')';
                ctx.lineWidth = 3;
                ctx.stroke();
                if (wave.alpha < 0.02 || wave.radius > wave.maxRadius) {
                    shockwaves.splice(w, 1);
                }
            }

            for (var i = 0; i < points.length; i++) {
                var p1 = points[i];
                p1.x += p1.vx;
                p1.y += p1.vy;

                // Fricción leve
                p1.vx *= 0.99;
                p1.vy *= 0.99;
                if (Math.abs(p1.vx) < 0.2) p1.vx += (Math.random() - 0.5) * 0.4;
                if (Math.abs(p1.vy) < 0.2) p1.vy += (Math.random() - 0.5) * 0.4;

                if (p1.x < 0 || p1.x > width) p1.vx *= -1;
                if (p1.y < 0 || p1.y > height) p1.vy *= -1;

                // Interacción de repelencia/atracción suave con el cursor
                if (mouse.x !== null) {
                    var mdx = p1.x - mouse.x;
                    var mdy = p1.y - mouse.y;
                    var mdist = Math.sqrt(mdx * mdx + mdy * mdy);
                    if (mdist < 180 && mdist > 5) {
                        var force = (180 - mdist) / 180;
                        p1.vx += (mdx / mdist) * force * 0.4;
                        p1.vy += (mdy / mdist) * force * 0.4;
                    }
                }

                var isLight = document.documentElement.classList.contains('light');
                var nodeColor = isLight ? 'rgba(5, 150, 105, ' : p1.color;

                // Dibujar Nodo (Resplandeciente Neón / Oscuro Institucional en Modo Claro)
                ctx.beginPath();
                ctx.arc(p1.x, p1.y, p1.radius, 0, Math.PI * 2);
                ctx.fillStyle = nodeColor + (isLight ? '0.85)' : '0.9)');
                ctx.shadowBlur = isLight ? 4 : 10;
                ctx.shadowColor = nodeColor + '0.6)';
                ctx.fill();
                ctx.shadowBlur = 0;

                // Dibujar Conexiones
                for (var j = i + 1; j < points.length; j++) {
                    var p2 = points[j];
                    var dx = p1.x - p2.x;
                    var dy = p1.y - p2.y;
                    var dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < maxDist) {
                        var alpha = (1 - dist / maxDist) * (isLight ? 0.65 : 0.55);
                        ctx.beginPath();
                        ctx.moveTo(p1.x, p1.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = nodeColor + alpha + ')';
                        ctx.lineWidth = isLight ? 1.3 : 1.1;
                        ctx.stroke();
                    }
                }

                // Láseres Neón con el Mouse
                if (mouse.x !== null) {
                    var mdx2 = p1.x - mouse.x;
                    var mdy2 = p1.y - mouse.y;
                    var mdist2 = Math.sqrt(mdx2 * mdx2 + mdy2 * mdy2);
                    if (mdist2 < 220) {
                        var malpha = (1 - mdist2 / 220) * 0.85;
                        ctx.beginPath();
                        ctx.moveTo(p1.x, p1.y);
                        ctx.lineTo(mouse.x, mouse.y);
                        ctx.strokeStyle = isLight ? 'rgba(5, 150, 105, ' + malpha + ')' : 'rgba(16, 185, 129, ' + malpha + ')';
                        ctx.lineWidth = 1.4;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        animate();
    })();

    // 2. Movimiento Interactivo 3D con Parallax para el Logo de Fondo y Figuras
    document.addEventListener('mousemove', function (e) {
        var mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
        var mouseY = (e.clientY / window.innerHeight - 0.5) * 2;

        var shapes = document.querySelectorAll('.bg-shape');
        shapes.forEach(function (shape, idx) {
            var speed = (idx + 1) * 35;
            var rot = (idx + 1) * 15 * mouseX;
            shape.style.transform = 'translate3d(' + (mouseX * speed) + 'px, ' + (mouseY * speed) + 'px, 0) rotate(' + rot + 'deg)';
        });

        var geoShapes = document.querySelectorAll('.geo-shape');
        geoShapes.forEach(function (geo, idx) {
            var speed = (idx + 1) * 45;
            var rot = (idx + 1) * 25 * mouseX;
            geo.style.transform = 'translate3d(' + (mouseX * speed * -1) + 'px, ' + (mouseY * speed * -1) + 'px, 0) rotate(' + rot + 'deg) scale(' + (1 + mouseX * 0.06) + ')';
        });

        // PARALLAX DEL LOGO INSTITUCIONAL EN EL FONDO (Movimiento suave en 3D)
        var watermark = document.querySelector('.institutional-watermark-overlay');
        if (watermark) {
            var moveX = mouseX * 65;
            var moveY = mouseY * 65;
            var rotDeg = mouseX * 8;
            var scaleFactor = 1 + Math.abs(mouseX) * 0.05;
            watermark.style.transform = 'translate(-50%, -50%) translate3d(' + moveX + 'px, ' + moveY + 'px, 0) rotate(' + rotDeg + 'deg) scale(' + scaleFactor + ')';
        }

        var cursorGlow = document.getElementById('cursor-interactive-glow');
        if (cursorGlow) {
            cursorGlow.style.left = e.clientX + 'px';
            cursorGlow.style.top = e.clientY + 'px';
        }
    });
</script>

<?php if (Session::isLoggedIn()): ?>
<div class="min-h-screen flex flex-col w-full relative z-10">
    <!-- Incluir Top Navigation Bar Horizontal -->
    <?php require_once APP_ROOT . '/views/layout/sidebar.php'; ?>
    
    <!-- Contenedor de contenido principal -->
    <div class="flex-1 overflow-y-auto w-full">
        <!-- Main Content Area -->
        <main class="p-6 md:p-8 w-full max-w-7xl mx-auto">
            <!-- Alertas Flash -->
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="auto-dismiss mb-6 px-4 py-3 rounded-xl border border-emerald-500/30 bg-emerald-950/40 text-emerald-300 flex items-center space-x-2 glass-panel shadow-lg">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
                    <span><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></span>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="auto-dismiss mb-6 px-4 py-3 rounded-xl border border-rose-500/30 bg-rose-950/40 text-rose-300 flex items-center space-x-2 glass-panel shadow-lg">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 text-base"></i>
                    <span><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></span>
                </div>
            <?php endif; ?>
<?php endif; ?>
