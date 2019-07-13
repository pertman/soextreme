<?php if (isUserConnected()): ?>
    <a href="<?php echo base_url(); ?>LoginController/disconnect" class="menu-btn disconnect-button">Déconnexion</a>
<?php else: ?>
    <a href="<?php echo base_url(); ?>LoginController/connect" class="menu-btn login-button">Connexion</a>
    <a href="<?php echo base_url(); ?>UserController/create" class="menu-btn subscrption-button">Inscription</a>
<?php endif; ?>

<a href="<?php echo base_url(); ?>ActivityController/createActivity" class="menu-btn subscrption-button">Création activité</a>