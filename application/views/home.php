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

<?php //@TODO remove index.php from url ?>
<a href="index.php/LoginController">Connexion</a>
<a href="index.php/UserController">Inscription</a>
