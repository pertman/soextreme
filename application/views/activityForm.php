<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Création activité</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
</head>
<body>
<form class="subscriptionForm">
    <label for="act_title">Titre</label>
    <input type="text" name="act_title" required>
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
</body>
</html>