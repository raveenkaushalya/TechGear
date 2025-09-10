<?php
/**
 * Common footer include
 * Contains closing tags and JavaScript references
 */
?>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main JavaScript -->
    <script src="assets/js/main.js"></script>
    
    <!-- Bootstrap Integration -->
    <script src="assets/js/bootstrap-integration.js"></script>
    
    <!-- Page-specific JavaScript -->
    <?php if (isset($pageSpecificJS)): ?>
        <?php foreach ($pageSpecificJS as $jsFile): ?>
            <script src="<?php echo $jsFile; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Page-specific inline JavaScript -->
    <?php if (isset($inlineJS)): ?>
        <script>
            <?php echo $inlineJS; ?>
        </script>
    <?php endif; ?>
</body>
</html>