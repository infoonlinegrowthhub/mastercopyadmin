</div>
</div>

<script src="<?php echo BASE_URL; ?>dist-admin/js/scripts.js"></script>
<script src="<?php echo BASE_URL; ?>dist-admin/js/custom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script src="<?php echo BASE_URL; ?>dist-admin/js/dashboard-charts.js"></script>

<!-- Include iziToast library for toast notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iziToast/1.4.0/css/iziToast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/iziToast/1.4.0/iziToa

<script>
    // Check if there are error messages
    <?php if (isset($error_message)): ?>
        iziToast.show({
            message: '<?php echo addslashes($error_message); ?>', // Escaping for JavaScript
            position: 'topRight',
            color: 'red',
        });
    <?php endif; ?>

    // Check if there are success messages
    <?php if (isset($success_message)): ?>
        iziToast.show({
            message: '<?php echo addslashes($success_message); ?>', // Escaping for JavaScript
            position: 'topRight',
            color: 'green',
        });
    <?php endif; ?>

    // Check for session-based success messages
    <?php if (isset($_SESSION['success_message'])): ?>
        iziToast.show({
            message: '<?php echo addslashes($_SESSION['success_message']); ?>', // Escaping for JavaScript
            position: 'topRight',
            color: 'green',
        });
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    // Check for session-based error messages
    <?php if (isset($_SESSION['error_message'])): ?>
        iziToast.show({
            message: '<?php echo addslashes($_SESSION['error_message']); ?>', // Escaping for JavaScript
            position: 'topRight',
            color: 'red',
        });
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
</script>

</body>
</html>