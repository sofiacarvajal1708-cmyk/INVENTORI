<?php if (Session::isLoggedIn()): ?>
        </main>
        
        <!-- Footer -->
        <footer class="glass-panel border-t border-white/10 py-4 px-6 text-center text-xs text-slate-400 mt-auto bg-slate-900/60">
            <p>&copy; <?= date('Y') ?> - IEBSM Barrio Santa Margarita | Solución de Trazabilidad, Control Fiscal y Sostenibilidad.</p>
        </footer>
    </div>
</div>
<?php endif; ?>

<!-- Scripts Globales -->
<script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
