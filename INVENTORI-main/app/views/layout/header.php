<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? $data['title'] . ' - ' : '' ?><?= APP_NAME ?></title>
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      if (typeof tailwind !== 'undefined') {
        tailwind.config = {
          darkMode: 'class',
          theme: {
            extend: {
              colors: {
                slate: {
                  850: '#151e2e',
                  950: '#080b14'
                }
              },
              fontFamily: {
                sans: ['Plus Jakarta Sans', 'Outfit', 'sans-serif'],
              }
            }
          }
        };
      }
    </script>
    <!-- FontAwesome para Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/img/logo.png?v=<?= time() ?>">
    <!-- Estilos personalizados con anti-caché -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css?v=<?= time() ?>">
    <script>
        window.BASE_URL = "<?= BASE_URL ?>";
        window.SESSION_TIMEOUT = <?= defined('SESSION_TIMEOUT') ? SESSION_TIMEOUT : 900 ?>;
        window.IS_LOGGED_IN = <?= Session::isLoggedIn() ? 'true' : 'false' ?>;

        /* ---- Selección Dinámica de Tema ---- */
        function applyAppTheme(mode) {
            var root = document.documentElement;
            var icons = document.querySelectorAll('#global-theme-icon, #theme-icon');
            var btns  = document.querySelectorAll('#global-theme-toggle, #theme-toggle');

            if (mode === 'light') {
                root.classList.add('light');
                icons.forEach(function(icon) {
                    icon.className = 'fa-solid fa-sun text-amber-500';
                });
                btns.forEach(function(btn) {
                    btn.title = 'Cambiar a Modo Oscuro';
                });
            } else {
                root.classList.remove('light');
                icons.forEach(function(icon) {
                    icon.className = 'fa-solid fa-moon text-blue-400';
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

            // Animar rotación suave del botón
            var btns = document.querySelectorAll('#global-theme-toggle, #theme-toggle');
            btns.forEach(function(btn) {
                btn.style.transform = 'rotate(180deg) scale(1.1)';
                setTimeout(function() { btn.style.transform = ''; }, 300);
            });

            // Toast elegante
            var toast = document.createElement('div');
            toast.className = 'fixed top-5 right-5 z-50 px-4 py-2.5 rounded-xl bg-slate-900/95 border border-slate-700 text-slate-200 shadow-xl text-xs font-semibold flex items-center space-x-2.5 backdrop-blur-md transition-all duration-300';
            toast.innerHTML = (newMode === 'light')
                ? '<i class="fa-solid fa-sun text-amber-400 text-sm"></i><span>Modo Claro Activado</span>'
                : '<i class="fa-solid fa-moon text-blue-400 text-sm"></i><span>Modo Oscuro Activado</span>';
            document.body.appendChild(toast);
            setTimeout(function() {
                toast.style.opacity = '0';
                setTimeout(function() { toast.remove(); }, 300);
            }, 1800);
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

<!-- Fondo Animado Dinámico con Constelaciones Interactivas y Luz Ambiental -->
<div class="animated-bg-wrapper pointer-events-none fixed inset-0 overflow-hidden z-0" aria-hidden="true">
    <div id="cursor-interactive-glow" class="cursor-glow-orb pointer-events-none"></div>
    <div class="bg-shape shape-blob-blue-1 pointer-events-none"></div>
    <div class="bg-shape shape-blob-green-1 pointer-events-none"></div>

    <!-- Canvas de Malla de Partículas Suaves e Interactivas -->
    <canvas id="geo-constellation-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0 opacity-50"></canvas>

    <!-- Marca de Agua Institucional con Parallax Sutil -->
    <div class="institutional-watermark-overlay pointer-events-none"></div>
</div>

<script>
    // Partículas Ambientales Elegantes en Canvas
    (function initGeoConstellationCanvas() {
        var canvas = document.getElementById('geo-constellation-canvas');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        var width, height;
        var points = [];
        var maxDist = 130;
        var mouse = { x: null, y: null };

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        var count = Math.min(55, Math.floor((width * height) / 22000));
        for (var i = 0; i < count; i++) {
            points.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                radius: Math.random() * 1.6 + 1.2
            });
        }

        document.addEventListener('mousemove', function(e) {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        function animate() {
            ctx.clearRect(0, 0, width, height);

            var isLight = document.documentElement.classList.contains('light');
            var dotColor = isLight ? 'rgba(37, 99, 235, ' : 'rgba(59, 130, 246, ';

            for (var i = 0; i < points.length; i++) {
                var p1 = points[i];
                p1.x += p1.vx;
                p1.y += p1.vy;

                if (p1.x < 0 || p1.x > width) p1.vx *= -1;
                if (p1.y < 0 || p1.y > height) p1.vy *= -1;

                // Reacción suave con el ratón
                if (mouse.x !== null) {
                    var mdx = p1.x - mouse.x;
                    var mdy = p1.y - mouse.y;
                    var mdist = Math.sqrt(mdx * mdx + mdy * mdy);
                    if (mdist < 130 && mdist > 5) {
                        var force = (130 - mdist) / 130;
                        p1.vx += (mdx / mdist) * force * 0.12;
                        p1.vy += (mdy / mdist) * force * 0.12;
                    }
                }

                // Dibujar Partícula
                ctx.beginPath();
                ctx.arc(p1.x, p1.y, p1.radius, 0, Math.PI * 2);
                ctx.fillStyle = dotColor + (isLight ? '0.5)' : '0.45)');
                ctx.fill();

                // Conexiones de Partículas
                for (var j = i + 1; j < points.length; j++) {
                    var p2 = points[j];
                    var dx = p1.x - p2.x;
                    var dy = p1.y - p2.y;
                    var dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < maxDist) {
                        var alpha = (1 - dist / maxDist) * (isLight ? 0.2 : 0.15);
                        ctx.beginPath();
                        ctx.moveTo(p1.x, p1.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = dotColor + alpha + ')';
                        ctx.lineWidth = 0.75;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        animate();
    })();

    // Parallax Sutil en Movimiento de Ratón
    document.addEventListener('mousemove', function (e) {
        var mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
        var mouseY = (e.clientY / window.innerHeight - 0.5) * 2;

        var watermark = document.querySelector('.institutional-watermark-overlay');
        if (watermark) {
            watermark.style.transform = 'translate(-50%, -50%) translate3d(' + (mouseX * 20) + 'px, ' + (mouseY * 20) + 'px, 0)';
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
    <!-- Top Navigation Bar Horizontal -->
    <?php require_once APP_ROOT . '/views/layout/sidebar.php'; ?>
    
    <!-- Contenedor Principal -->
    <div class="flex-1 overflow-y-auto w-full">
        <main class="p-6 md:p-8 w-full max-w-7xl mx-auto">
            <!-- Alertas Flash -->
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="auto-dismiss mb-6 px-4 py-3 rounded-xl border border-emerald-500/30 bg-emerald-950/40 text-emerald-300 flex items-center space-x-3 glass-panel shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                    <span class="font-semibold text-sm"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></span>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="auto-dismiss mb-6 px-4 py-3 rounded-xl border border-rose-500/30 bg-rose-950/40 text-rose-300 flex items-center space-x-3 glass-panel shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 text-lg"></i>
                    <span class="font-semibold text-sm"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></span>
                </div>
            <?php endif; ?>
<?php endif; ?>
