<head>
    <title>Inscription</title>
    <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
</head>

<form class="subscriptionForm">
    <label for="usr_firstname">Prénom</label>
    <input type="text" name="usr_firstname" required>
    <label for="usr_lastname">Nom</label>
    <input type="text" name="usr_lastname" required>
    <label for="usr_email">Email</label>
    <input type="mail" name="usr_email" required>
    <label for="usr_password">Mot de passe</label>
    <input type="password" name="usr_password" required>
    <label for="usr_password_2">Confirmation Mot de passe</label>
    <input type="password" name="usr_password_2" required>
    <label for="usr_phone">Téléphone</label>
    <input type="text" name="usr_phone" required>
    <input type="submit" value="Valider">
</form>

<script>
    $('.subscriptionForm').on('submit', function (e) {
        e.preventDefault();

        let data = $('.subscriptionForm').serialize();

        $.ajax({
            type: "post",
            data: data,
            url: "http://www.soextreme.code/UserController/create",
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