<?php if (Session::isLoggedIn()): ?>
        </main>
        
        <!-- Footer -->
        <footer class="glass-panel border-t border-white/5 py-4 px-6 text-center text-xs text-slate-400 mt-auto">
            <p>&copy; <?= date('Y') ?> - IEBSM Barrio Santa Margarita | Solución de Trazabilidad, Control Fiscal y Sostenibilidad.</p>
        </footer>
    </div>
</div>
<?php endif; ?>

<!-- Scripts Globales -->
<script src="<?= BASE_URL ?>/public/js/main.js"></script>

<!-- Lógica del Menú Desplegable en Móvil -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const mainSidebar = document.getElementById('main-sidebar');

        if (sidebarToggle && mainSidebar) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                mainSidebar.classList.toggle('-translate-x-full');
            });

            // Cerrar el panel al hacer clic en cualquier parte fuera del sidebar
            document.addEventListener('click', (e) => {
                if (window.innerWidth < 768) { // Solo en resoluciones móviles
                    if (!mainSidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        mainSidebar.classList.add('-translate-x-full');
                    }
                }
            });
        }
    });
</script>
</body>
</html>
