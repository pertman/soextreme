<head>
    <title>Accueil</title>
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
</head>

<a href="<?php echo base_url("/LoginController/showLoginForm"); ?>" class="menu-btn login-button">Connexion</a>
<a href="<?php echo base_url("/UserController/showSubscriptionForm"); ?>" class="menu-btn subscrption-button">Inscription</a>
<a href="<?php echo base_url("/ActivityController/showActivityForm"); ?>" class="menu-btn subscrption-button">Création activité</a>
<a class="menu-btn disconnect-button user-connected">Déconnexion</a>

<script>
    $(document).ready(function() {
        manageButtonsOnConnectionStatus();

        //$('.login-button').click(function () {
        //    window.location.replace(<?php //echo base_url("/LoginController/showLoginForm"); ?>//);
        //});

        //$('.subscrption-button').click(function () {
        //    window.location.replace("<?php //echo base_url("/UserController/showSubscriptionForm"); ?>//");
        //});

        $('.disconnect-button').click(function () {
            $.ajax({
                type: "get",
                url: "<?php echo base_url("/LoginController/disconnect"); ?>",
                dataType:"json",
                success: function (response) {
                    if (response.status === 'valid'){
                        manageButtonsOnConnectionStatus();
                    }
                }
            })
        });

    });

    function manageButtonsOnConnectionStatus() {
        $.ajax({
            type: "get",
            url: "<?php echo base_url("/SessionController/isLoggedIn"); ?>",
            dataType:"json",
            success: function (response) {
                let connected = false;
                if (response.status === 'valid'){
                    connected = true;
                }

                $('.menu-btn').each(function (index, value) {
                    if (value.classList.contains('user-connected')){
                        if (connected){
                            $(this).show();
                        }else{
                            $(this).hide();
                        }
                    }else{
                        if (connected){
                            $(this).hide();
                        }else{
                            $(this).show();
                        }
                    }
                });
            }
        });
    }
</script>