<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? $data['title'] . ' - ' : '' ?><?= APP_NAME ?></title>
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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
      }
    </script>
    <!-- FontAwesome para Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <script>
        // Variables globales para JS
        window.BASE_URL = "<?= BASE_URL ?>";
        window.SESSION_TIMEOUT = <?= defined('SESSION_TIMEOUT') ? SESSION_TIMEOUT : 900 ?>;
        window.IS_LOGGED_IN = <?= Session::isLoggedIn() ? 'true' : 'false' ?>;
    </script>
</head>
<body class="h-full flex flex-col">
<?php if (Session::isLoggedIn()): ?>
<div class="flex h-screen overflow-hidden w-full">
    <!-- Incluir Sidebar -->
    <?php require_once APP_ROOT . '/views/layout/sidebar.php'; ?>
    
    <!-- Contenedor de contenido principal -->
    <div class="flex flex-col flex-1 overflow-y-auto w-full">
        <!-- Top Navbar -->
        <header class="glass-panel border-b border-white/5 flex items-center justify-between px-6 py-4 z-10 w-full">
            <div class="flex items-center space-x-2">
                <button id="sidebar-toggle" class="text-slate-400 hover:text-white md:hidden p-2 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-violet-400 via-fuchsia-400 to-cyan-400">
                    <?= isset($data['title']) ? $data['title'] : 'Dashboard' ?>
                </h1>
            </div>
            
            <!-- Perfil de usuario -->
            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex flex-col text-right">
                    <span class="text-sm font-semibold text-white"><?= Session::get('user_name') ?></span>
                    <span class="text-xs text-slate-400 font-medium tracking-wide uppercase"><?= Session::get('role_name') ?></span>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-violet-600 to-cyan-500 flex items-center justify-center text-white font-bold shadow-lg shadow-violet-500/20">
                    <?= strtoupper(substr(Session::get('user_name'), 0, 1)) ?>
                </div>
                <!-- Cerrar Sesión -->
                <a href="<?= BASE_URL ?>/auth/logout" class="text-slate-400 hover:text-rose-400 transition-colors p-2" title="Cerrar Sesión">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                </a>
            </div>
        </header>
        
        <!-- Main Content Area -->
        <main class="flex-1 p-6 md:p-8 w-full max-w-7xl mx-auto">
            <!-- Alertas Flash -->
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="auto-dismiss mb-6 px-4 py-3 rounded-lg border border-emerald-500/20 bg-emerald-950/30 text-emerald-300 flex items-center space-x-2 glass-panel">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></span>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="auto-dismiss mb-6 px-4 py-3 rounded-lg border border-rose-500/20 bg-rose-950/30 text-rose-300 flex items-center space-x-2 glass-panel">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
                    <span><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></span>
                </div>
            <?php endif; ?>
<?php endif; ?>
