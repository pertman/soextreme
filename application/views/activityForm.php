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
    <input class="input" type="text" name="act_title" required>
    <label for="usr_lastname">Catégorie</label>
    <div class="select">
        <select>
            <option value="0">Selectionner</option>
            <?php if (isset($category)) {
                foreach($category as $category) { ?>
                    <option value="<?php echo $category['cat_id']; ?>"><?php echo $category['cat_name']; ?></option>
                <?php }
            } ?>
        </select>
    </div>
    <label for="act_description">Description</label>
    <textarea class="textarea" name="act_description" placeholder="Description de l'activité..."></textarea>
    <label for="act_description">Description courte</label>
    <textarea class="textarea" name="act_description_short" placeholder="Description courte de l'activité..."></textarea>


    <!--Upload picture-->
    <div class="file is-boxed">
        <label class="file-label">
            <input class="file-input" type="file" name="resume">
            <span class="file-cta">
      <span class="file-icon">
        <i class="fas fa-upload"></i>
      </span>
      <span class="file-label">
        Sélectionnez une image
      </span>
    </span>
        </label>
    </div>
    <!--    -->

    <!--Upload video-->
    <div class="file is-boxed">
        <label class="file-label">
            <input class="file-input" type="file" name="resume">
            <span class="file-cta">
      <span class="file-icon">
        <i class="fas fa-upload"></i>
      </span>
      <span class="file-label">
        Sélectionnez une vidéo
      </span>
    </span>
        </label>
    </div>
    <!--    -->


    <label class="checkbox">
        L'activité fait partie d'une offre spéciale
        <input type="checkbox">
    </label>
    <textarea class="textarea" placeholder="Description de l'offre spéciale..."></textarea>

    Visibilité du projet :
    <div class="field">
        <input id="switchRtlExample" type="checkbox" name="switchRtlExample" class="switch is-rtl" checked="checked">
        <label for="switchRtlExample">Switch example</label>
    </div>

    <input type="submit" value="Valider">
</form>
</body>
</html>