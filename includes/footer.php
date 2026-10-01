<<<<<<< HEAD
</main>
   <footer class="main-footer text-center">
        <div class="container">
            <p>&copy; <?= date('Y') ?> SmartSpend - Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Carga dinámica del archivo JavaScript -->
    <script src="<?= $base_path ?>assets/js/auth-uservalidation.js"></script>
</body>
</html>
=======
    </main>

    <footer class="site-footer" role="contentinfo">
        <div class="container footer-content">
            <p>&copy; <?php echo date('Y'); ?> <strong>SmartSpend</strong>. Sistema Web de Control y Gestión de Gastos Personales.</p>
            
            <nav aria-label="Navegación secundaria del pie de página">
                <ul class="footer-nav">
                    <li><a href="#accessibility" class="footer-link">Declaración de Accesibilidad (WCAG 2.2)</a></li>
                    <li><a href="#terms" class="footer-link">Términos del Servicio</a></li>
                </ul>
            </nav>
        </div>
    </footer>
    <script src="<?php echo $basePath; ?>assets/js/main.js"></script>
    <?php if (!empty($extraScripts) && is_array($extraScripts)): ?>
        <?php foreach ($extraScripts as $script): ?>
            <?php if ($script !== 'assets/js/main.js'): ?>
                <script src="<?php echo (isset($basePath) ? htmlspecialchars($basePath) : '') . htmlspecialchars($script); ?>"></script>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
>>>>>>> c749ca896438d59128bb5756d8d02b60d84adb63
