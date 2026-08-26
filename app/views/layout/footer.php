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
    document.addEventListener('DOMContentLoaded', function () {
        var sidebarToggle = document.getElementById('sidebar-toggle');
        var mainSidebar   = document.getElementById('main-sidebar');

        if (sidebarToggle && mainSidebar) {
            sidebarToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                mainSidebar.classList.toggle('-translate-x-full');
            });

            document.addEventListener('click', function (e) {
                if (window.innerWidth < 768) {
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
