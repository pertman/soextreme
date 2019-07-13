<?php foreach ($_SESSION['messages'] as $key => $message): ?>
    <div class="notification is-link">
        <button class="delete"></button>
        <?php echo $message; ?>
    </div>
    <?php unset($_SESSION['messages'][$key]); ?>
<?php endforeach; ?>
<?php foreach ($messages as $message): ?>
    <div class="notification is-danger">
        <button class="delete"></button>
        <?php echo $message; ?>
    </div>
<?php endforeach; ?>
