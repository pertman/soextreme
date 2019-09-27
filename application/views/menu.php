<nav class="navbar">
    <div class="navbar-brand">
        <a class="navbar-item" href="<?php echo base_url(); ?>">
            <img src="<?php echo base_url(); ?>/application/assets/images/logo4.png" alt="icon soextreme" width="112" height="28">
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
                        <a href="<?php echo base_url("ActivityController/listActivities"); ?>" class="navbar-item">Activités</a>
                    </p>
                    <div class="navbar-dropdown is-boxed">
                        <a href="<?php echo base_url("AdminActivityController/createActivity"); ?>" class="navbar-item">Créer</a>
                        <a href="<?php echo base_url("ActivityController/listActivities"); ?>" class="navbar-item">Liste</a>
                    </div>
                </div>

                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-link">
                        <a href="<?php echo base_url("AdminCategoryController/listCategory"); ?>" class="navbar-item">Catégories</a>
                    </p>
                    <div class="navbar-dropdown is-boxed">
                        <a href="<?php echo base_url("AdminCategoryController/createCategory"); ?>" class="navbar-item">Créer</a>
                        <a href="<?php echo base_url("AdminCategoryController/listCategory"); ?>" class="navbar-item">Liste</a>
                    </div>
                </div>

                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-link">
                        <a href="<?php echo base_url("AdminMenuController/listMenu"); ?>" class="navbar-item">Menu</a>
                    </p>
                    <div class="navbar-dropdown is-boxed">
                        <a href="<?php echo base_url("AdminMenuController/createMenu"); ?>" class="navbar-item">Créer</a>
                        <a href="<?php echo base_url("AdminMenuController/listMenu"); ?>" class="navbar-item">Liste</a>
                    </div>
                </div>

                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-link">
                        <a href="<?php echo base_url("AdminPromotionController/listPromotion"); ?>" class="navbar-item">Promotion</a>
                    </p>
                    <div class="navbar-dropdown is-boxed">
                        <a href="<?php echo base_url("AdminPromotionController/createPromotion"); ?>" class="navbar-item">Créer</a>
                        <a href="<?php echo base_url("AdminPromotionController/listPromotion"); ?>" class="navbar-item">Liste</a>
                    </div>
                </div>
                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-link">
                        <a href="<?php echo base_url("AdminRequestController/requests"); ?>" class="navbar-item">Demandes</a>
                    </p>
                    <div class="navbar-dropdown is-boxed">
                        <a href="<?php echo base_url("AdminRequestController/openRequests"); ?>" class="navbar-item">Demandes ouverts</a>
                        <a href="<?php echo base_url("AdminRequestController/closedRequests"); ?>" class="navbar-item">Demandes fermées</a>
                        <a href="<?php echo base_url("AdminRequestController/paybackRequests"); ?>" class="navbar-item">Demandes de remboursement</a>
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
                <a href="<?php echo base_url("UserController/profile"); ?>" class="navbar-item">Mon profil</a>
                <a href="<?php echo base_url("LoginController/disconnect"); ?>" class="navbar-item">Déconnexion</a>
				<!-- <a href="#" class="navbar-item"><i class="fas fa-shopping-cart"></i></a>-->
				
            <?php endif; ?>
            <?php if(isCurrentUserNotLoggedIn()): ?>
                <?php //@TODO remove dev autoconnect ?>
                <a href="<?php echo base_url("AdminController/autoconnect"); ?>" class="navbar-item">Admin Autoconnect dev</a>
                <a href="javascript:void(0);" id="connexion-modal" class="navbar-item">Connexion</a>
                <a href="javascript:void(0);" id="inscription-modal" class="navbar-item">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</nav>