<head>
    <title>Accueil</title>
    <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
</head>

<?php //unset($_SESSION['id']); ?>

<a href="LoginController">Connexion</a>
<a class="subscrption-button">Inscription</a>

<script>
    $(document).ready(function() {
        $('.subscrption-button').click(function () {
            $.ajax({
                type: "get",
                url: "http://www.soextreme.code/UserController",
                dataType:"json",
                success: function (response) {
                    if (response.status === 'valid'){
                        window.location.replace("http://www.soextreme.code/UserController/showSubscriptionForm");
                    }
                }
            })
        });
    });
</script>