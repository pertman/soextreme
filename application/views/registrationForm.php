<?php
//    @todo use parent template
    if (isset($messages)){
        foreach ($messages as $key => $message){
            echo $message;
            unset($messages[$key]);
        }
    }

    if (isset($_SESSION['$messages'])){
        foreach ($_SESSION['$messages'] as $key => $message){
            echo $message;
            unset($_SESSION['$messages'][$key]);
        }
    }
?>

<form action="<?php base_url() ?>UserController/create" method="post">
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