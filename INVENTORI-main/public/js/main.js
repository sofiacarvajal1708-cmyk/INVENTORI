/**
 * Script Principal del Sistema IEBSM
 */
document.addEventListener('DOMContentLoaded', () => {
    // Configuración del Temporizador de Inactividad (15 Minutos)
    const TIMEOUT_MS = (window.SESSION_TIMEOUT || 900) * 1000; // 900 segundos por defecto
    let inactivityTimer;

    function resetTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(logoutUser, TIMEOUT_MS);
    }

    function logoutUser() {
        // Redirigir al controlador de logout con parámetro de tiempo de expiración
        const logoutUrl = (window.BASE_URL || '') + '/auth/logout?timeout=1';
        window.location.href = logoutUrl;
    }

    // Registrar eventos para detectar actividad solo si el usuario ha iniciado sesión
    if (window.IS_LOGGED_IN) {
        const events = ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart'];
        events.forEach(eventName => {
            document.addEventListener(eventName, resetTimer, true);
        });

        // Iniciar temporizador
        resetTimer();
        console.log('Control de inactividad de sesión iniciado. Tiempo límite: ' + (TIMEOUT_MS / 1000) + ' segundos.');
    }

    // Auto-ocultar alertas temporales después de 5 segundos
    const alerts = document.querySelectorAll('.auto-dismiss');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
