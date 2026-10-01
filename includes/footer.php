    </main>

    <footer class="site-footer" role="contentinfo">
        <div class="container footer-content">
            <p>&copy; <?php echo date('Y'); ?> <strong>SmartSpend</strong>. Sistema Web de Control y Gestión de Gastos Personales.</p>
            
            <nav aria-label="Navegación secundaria del pie de página">
                <ul class="footer-nav">
                    <li><a href="<?= e($basePath) ?>views/about.php#accessibility" class="footer-link">Declaración de Accesibilidad (WCAG 2.2)</a></li>
                    <li><a href="<?= e($basePath) ?>views/about.php#terms" class="footer-link">Términos del Servicio</a></li>
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
