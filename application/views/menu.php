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
            <?php if (isCurrentUserAdmin()): ?>
                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-link">
                        Activités
                    </p>
                    <div class="navbar-dropdown is-boxed">
                        <a href="<?php echo base_url("AdminActivityController/createActivity"); ?>" class="navbar-item">Créer</a>
                        <a href="<?php echo base_url("ActivityController/listActivities"); ?>" class="navbar-item">Liste</a>
                    </div>
                </div>

                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-link">
                        Catégories
                    </p>
                    <div class="navbar-dropdown is-boxed">
                        <a href="<?php echo base_url("AdminCategoryController/createCategory"); ?>" class="navbar-item">Créer</a>
                        <a href="<?php echo base_url("AdminCategoryController/listCategory"); ?>" class="navbar-item">Liste</a>
                    </div>
                </div>

                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-link">
                        Menu
                    </p>
                    <div class="navbar-dropdown is-boxed">
                        <a href="<?php echo base_url("AdminMenuController/createMenu"); ?>" class="navbar-item">Créer</a>
                        <a href="<?php echo base_url("AdminMenuController/listMenu"); ?>" class="navbar-item">Liste</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isCurrentUserCustomer()): ?>
                <a class="navbar-item" href="<?php echo base_url("ActivityController/listActivities"); ?>">
                    Activitées
                </a>
                <?php if (isset($categories)): ?>
                    <?php foreach ($categories as $catId => $category): ?>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <p class="navbar-link">
                                <?php echo $category['name']; ?>
                            </p>
                            <div class="navbar-dropdown is-boxed">
                                <?php foreach ($category['activities'] as $actId => $activity): ?>
                                    <a href="<?php echo base_url("ActivityController/seeActivity"); ?>?id=<?php echo $actId; ?>" class="navbar-item"><?php echo $activity['name']; ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="navbar-end">
            <?php if (isCurrentUserAdmin()): ?>
                <a href="<?php echo base_url("AdminController/disconnect"); ?>" class="navbar-item">Déconnexion</a>
            <?php endif; ?>
            <?php if (isCurrentUserCustomer()): ?>
                <a href="<?php echo base_url("LoginController/disconnect"); ?>" class="navbar-item">Déconnexion</a>
            <?php endif; ?>
            <?php if(isCurrentUserNotLoggedIn()): ?>
                <?php //@TODO remove dev autoconnect ?>
                <a href="<?php echo base_url("AdminController/autoconnect"); ?>" class="navbar-item">Admin Autoconnect dev</a>
                <a href="<?php echo base_url("LoginController/connect"); ?>" class="navbar-item">Connexion</a>
                <a href="<?php echo base_url("UserController/create"); ?>" class="navbar-item">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</nav>