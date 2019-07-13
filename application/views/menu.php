<nav class="navbar is-transparent">
    <div class="navbar-brand">
        <a class="navbar-item" href="<?php echo base_url(); ?>">
            <img src="https://bulma.io/images/bulma-logo.png" alt="Bulma: a modern CSS framework based on Flexbox" width="112" height="28">
        </a>
        <div class="navbar-burger burger" data-target="navbarExampleTransparentExample">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div id="navbarExampleTransparentExample" class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item" href="<?php echo base_url(); ?>">Accueil</a>
            <a href="<?php echo base_url(); ?>ActivityController/createActivity" class="navbar-item">Création activité</a>

<!--            <div class="navbar-item has-dropdown is-hoverable">-->
<!--                <a class="navbar-link" href="https://bulma.io/documentation/overview/start/">-->
<!--                    Docs-->
<!--                </a>-->
<!--                <div class="navbar-dropdown is-boxed">-->
<!--                    <a class="navbar-item" href="https://bulma.io/documentation/overview/start/">-->
<!--                        Overview-->
<!--                    </a>-->
<!--                    <a class="navbar-item" href="https://bulma.io/documentation/modifiers/syntax/">-->
<!--                        Modifiers-->
<!--                    </a>-->
<!--                    <a class="navbar-item" href="https://bulma.io/documentation/columns/basics/">-->
<!--                        Columns-->
<!--                    </a>-->
<!--                    <a class="navbar-item" href="https://bulma.io/documentation/layout/container/">-->
<!--                        Layout-->
<!--                    </a>-->
<!--                    <a class="navbar-item" href="https://bulma.io/documentation/form/general/">-->
<!--                        Form-->
<!--                    </a>-->
<!--                    <hr class="navbar-divider">-->
<!--                    <a class="navbar-item" href="https://bulma.io/documentation/elements/box/">-->
<!--                        Elements-->
<!--                    </a>-->
<!--                    <a class="navbar-item is-active" href="https://bulma.io/documentation/components/breadcrumb/">-->
<!--                        Components-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->

        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <div class="field is-grouped">
                    <?php if (isUserConnected()): ?>
                        <p class="control">
                            <a href="<?php echo base_url(); ?>LoginController/disconnect" class="navbar-item">Déconnexion</a>
                        </p>
                    <?php else: ?>
                        <p class="control">
                            <a href="<?php echo base_url(); ?>LoginController/connect" class="navbar-item">Connexion</a>
                        </p>
                        <p class="control">
                            <a href="<?php echo base_url(); ?>UserController/create" class="navbar-item">Inscription</a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>