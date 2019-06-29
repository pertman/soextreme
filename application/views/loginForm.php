<head>
    <title>Connexion</title>
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
</head>

<form class="loginForm">
    <label for="usr_email">Email</label>
    <input type="mail" name="usr_email" required>
    <label for="usr_password">Mot de passe</label>
    <input type="password" name="usr_password" required>
    <input type="submit" value="Valider">
</form>

<script>
    $('.loginForm').on('submit', function (e) {
        e.preventDefault();

        let data = $('.loginForm').serialize();

        $.ajax({
            type: "post",
            headers : {
                'CsrfToken': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            url: "http://www.soextreme.code/LoginController/connect",
            dataType:"json",
            success: function (response) {
                if (response.status === 'valid'){

                    redirectPost("http://www.soextreme.code/MainController", {messages: response.messages})
                }else{
                    let messages = response.messages;
                    console.log(messages);
                }
            }
        });
    });

    function redirectPost(location, args){
        let form = '';

        $.each( args, function( key, value ) {
            form += '<input type="hidden" name="'+key+'" value="'+value+'">';
        });

        $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
    }
</script>