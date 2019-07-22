<form method="post" action="createActivity">
    <div class="field">
        <label for="act_title">Titre</label>
        <div class="control">
            <input class="input" type="text" name="act_name" required>
        </div>
    </div>
    <div class="field">
        <label for="cat_id">Catégorie</label>
        <div class="control">
            <div class="select">
                <select class="select" name="cat_id">
                    <option value="">Selectionnez une catégorie</option>
                    <?php if (isset($category)) : ?>
                        <?php foreach($category as $category) : ?>
                            <option value="<?php echo $category['cat_id']; ?>"><?php echo $category['cat_name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="field">
        <label for="act_description">Description</label>
        <div class="control">
            <textarea class="textarea" name="act_description" placeholder="Description de l'activité..." required></textarea>
        </div>
    </div>
    <div class="field">
        <label for="act_resume">Description courte</label>
        <div class="control">
            <textarea class="textarea" name="act_resume" placeholder="Description courte de l'activité..." required></textarea>
        </div>
    </div>
    <div class="field">
        <label for="act_base_price">Prix hors promotion (€)</label>
        <div class="control">
            <input class="input" type="number" min="0.00" step="0.01" name="act_base_price" required>
        </div>
    </div>
    <div class="field">
        <label for="act_duration">Durée d'une session (min)</label>
        <div class="control">
            <input class="input" type="number" min="1" name="act_duration" required>
        </div>
    </div>
    <div class="field">
        <label for="act_monitor_nb">Nombre de moniteur requis</label>
        <div class="control">
            <input class="input" type="number" min="0" name="act_monitor_nb" required>
        </div>
    </div>
    <div class="field">
        <label for="act_operator_nb">Nombre d'opérateur requis</label>
        <div class="control">
            <input class="input" type="number" min="0" name="act_operator_nb" required>
        </div>
    </div>
    <div class="field">
        <label class="checkbox">
            L'activité fait partie d'une offre spéciale ?
            <input type="checkbox" name="act_is_special_offer">
        </label>
    </div>
    <div class="field">
        <div class="control">
            <textarea class="textarea" name="act_description_special_offer" placeholder="Description de l'offre spéciale..."></textarea>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button class="button is-link">Valider</button>
        </div>
    </div>
</form>