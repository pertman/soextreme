<nav class="navbar is-transparent">
    <div class="navbar-brand">
        <a class="navbar-item" href="<?php echo base_url(); ?>">
            <img src="https://bulma.io/images/bulma-logo.png" alt="Bulma: a modern CSS framework based on Flexbox" width="112" height="28">
        </a>
        <div class="navbar-burger burger" data-target="navbar">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div id="navbar" class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item" href="<?php echo base_url(); ?>">Accueil</a>

            <div class="navbar-item has-dropdown is-hoverable">
                <p class="navbar-link">
                    Activités
                </p>
                <div class="navbar-dropdown is-boxed">
                    <a href="<?php echo base_url(); ?>ActivityController/createActivity" class="navbar-item">Créer</a>
                    <a href="<?php echo base_url(); ?>ActivityController/showActivities" class="navbar-item">Voir</a>
                    <a href="<?php echo base_url(); ?>ActivityController/planActivity" class="navbar-item">Planifier</a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <p class="navbar-link">
                    Catégories
                </p>
                <div class="navbar-dropdown is-boxed">
                    <a href="<?php echo base_url(); ?>CategoryController/createCategory" class="navbar-item">Créer</a>
                </div>
            </div>
        </div>

        <div class="navbar-end">
            <?php if (isUserConnected()): ?>
                <a href="<?php echo base_url(); ?>LoginController/disconnect" class="navbar-item">Déconnexion</a>
            <?php else: ?>
                <a href="<?php echo base_url(); ?>LoginController/connect" class="navbar-item">Connexion</a>
                <a href="<?php echo base_url(); ?>UserController/create" class="navbar-item">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</nav>