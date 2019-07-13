<form class="subscriptionForm">
    <div class="field">
        <label for="act_title">Titre</label>
        <div class="control">
            <input class="input" type="text" name="act_title" required>
        </div>
    </div>
    <div class="field">
        <label for="usr_lastname">Catégorie</label>
        <div class="control">
            <div class="select">
                <select class="select">
                    <option value="">Selectionnez une catégorie</option>
                    <?php if (isset($category)) {
                        foreach($category as $category) { ?>
                            <option value="<?php echo $category['cat_id']; ?>"><?php echo $category['cat_name']; ?></option>
                        <?php }
                    } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="field">
        <label for="act_description">Description</label>
        <div class="control">
            <textarea class="textarea" placeholder="Description de l'activité..."></textarea>
        </div>
    </div>
    <div class="field">
        <label for="act_description">Description courte</label>
        <div class="control">
            <textarea class="textarea" placeholder="Description courte de l'activité..."></textarea>
        </div>
    </div>

    <div class="field">
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

    </div>

    <div class="field">
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
    </div>

    <div class="field">
        <label class="checkbox">
            L'activité fait partie d'une offre spéciale
            <input type="checkbox">
        </label>
    </div>
    <div class="field">
        <textarea class="textarea" placeholder="Description de l'offre spéciale..."></textarea>
    </div>

<!--    Visibilité du projet :-->
<!--    <div class="field">-->
<!--        <input id="switchRtlExample" type="checkbox" name="switchRtlExample" class="switch is-rtl" checked="checked">-->
<!--        <label for="switchRtlExample">Switch example</label>-->
<!--    </div>-->

    <div class="field">
        <div class="control">
            <button class="button is-link">Valider</button>
        </div>
    </div>
</form>