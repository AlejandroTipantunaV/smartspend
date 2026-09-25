</main>
    <footer class="main-footer">
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> SmartSpend - Todos los derechos reservados.</p>
        </div>
    </footer>
    <?php if (!empty($extraScripts) && is_array($extraScripts)): ?>
        <?php foreach ($extraScripts as $script): ?>
            <script src="<?php echo (isset($basePath) ? htmlspecialchars($basePath) : '') . htmlspecialchars($script); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
