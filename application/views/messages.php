<?php foreach ($_SESSION['messages'] as $key => $message): ?>
    <h4><?php echo $message; ?></h4>
    <?php unset($_SESSION['messages'][$key]); ?>
<?php endforeach; ?>
<?php foreach ($messages as $message): ?>
    <h4><?php echo $message; ?></h4>
<?php endforeach; ?>
