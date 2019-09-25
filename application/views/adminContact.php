<div class="page-title">
    Contact Administration
</div>

<form method="post" class="adminContactForm" action="contactAdmin">
    <div class="field">
        <label for="adr_subject">Sujet</label>
        <div class="control">
            <input class="input" type="text" name="adr_subject" value="" required>
        </div>
    </div>
    <div class="field">
        <label for="adr_description">Description</label>
        <div class="control">
            <textarea class="textarea" name="adr_description" placeholder="Description.." required></textarea>
        </div>
    </div>
    <div class="field buttons">
        <div class="control">
            <button class="button is-link">Envoyer</button>
        </div>
    </div>
</form>
