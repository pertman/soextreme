<form class="subscriptionForm" method="post" action="create">
    <div class="columns">
        <div class="column">
            <div class="field">
                <label for="usr_firstname">Prénom</label>
                <div class="control">
                    <input type="text" class="input" name="usr_firstname" required>
                </div>
            </div>
            <div class="field">
                <label for="usr_lastname">Nom</label>
                <div class="control">
                    <input type="text" class="input" name="usr_lastname" required>
                </div>
            </div>
            <div class="field">
                <label for="usr_email">Email</label>
                <div class="control">
                    <input type="mail" class="input" name="usr_email" required>
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
                    <input type="text" class="input" name="usr_phone" required>
                </div>
            </div>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button class="button is-link">Valider</button>
        </div>
    </div>
</form>