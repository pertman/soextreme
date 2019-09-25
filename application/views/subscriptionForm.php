<div class="page-title">
    Inscription
</div>

<?php
    $firstName    = '';
    $lastName     = '';
    $email        = isset($mail) ? $mail : '';
    $phone        = '';
    $isGift       = isset($mail);
    if (isset($post)){
        $firstName    = $post['usr_firstname'];
        $lastName     = $post['usr_lastname'];
        $email        = $post['usr_email'];
        $phone        = $post['usr_phone'];
    }
?>

<form method="post" action="create" enctype="multipart/form-data">
    <div class="columns">
        <div class="column">
            <div class="field">
                <label for="usr_firstname">Prénom</label>
                <div class="control">
                    <input type="text" class="input" name="usr_firstname" value="<?php echo $firstName; ?>" required>
                </div>
            </div>
            <div class="field">
                <label for="usr_lastname">Nom</label>
                <div class="control">
                    <input type="text" class="input" name="usr_lastname" value="<?php echo $lastName; ?>" required>
                </div>
            </div>
            <div class="field">
                <label for="usr_email">Email</label>
                <div class="control">
                    <input type="mail" class="input" name="usr_email" value="<?php echo $email; ?>" required <?php if ($isGift): ?>disabled<?php endif; ?>>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="field">
                <label for="usr_password">Mot de passe</label>
                <div class="control">
                    <input type="password" class="input" name="usr_password" required>
                </div>
            </div>
            <div class="field">
                <label for="usr_password_2">Confirmation Mot de passe</label>
                <div class="control">
                    <input type="password" class="input" name="usr_password_2" required>
                </div>
            </div>
            <div class="field">
                <label for="usr_phone">Téléphone</label>
                <div class="control">
                    <input type="text" class="input" name="usr_phone" value="<?php echo $phone; ?>" required>
                </div>
            </div>
        </div>
    </div>
    <div class="field">
        <label for="usr_profile_picture">Photo de profil</label>
        <div class="control">
            <input type="file" name="usr_profile_picture" id="usr_profile_picture">
        </div>
    </div>
    <?php if ($isGift): ?>
        <input type="hidden" name="usr_email" value="<?php echo $email; ?>">
    <?php endif; ?>
    <div class="buttons">
        <div class="field">
            <div class="control">
                <button class="button is-link">Valider</button>
            </div>
        </div>
    </div>
</form>