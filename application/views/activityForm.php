<?php //@TODO Title on each view ?>
<?php $isAct = (isset($activity)) ? true : false; ?>

<?php $actName                      = ($isAct) ? $activity['act_name']: ""; ?>
<?php $actCatId                     = ($isAct) ? $activity['cat_id']: ""; ?>
<?php $actDescription               = ($isAct) ? $activity['act_description']: ""; ?>
<?php $actResume                    = ($isAct) ? $activity['act_resume']: ""; ?>
<?php $actBasePrice                 = ($isAct) ? $activity['act_base_price']: ""; ?>
<?php $actDuration                  = ($isAct) ? $activity['act_duration']: ""; ?>
<?php $actMonitorNb                 = ($isAct) ? $activity['act_monitor_nb']: ""; ?>
<?php $actParticipantNb             = ($isAct) ? $activity['act_participant_nb']: ""; ?>
<?php $actOperatorNb                = ($isAct) ? $activity['act_operator_nb']: ""; ?>
<?php $actRequiredAge               = ($isAct) ? $activity['act_required_age']: ""; ?>
<?php $actIsSpecialOffer            = ($isAct) ? $activity['act_is_special_offer']: ""; ?>
<?php $actDescriptionSpecialOffer   = ($isAct) ? $activity['act_description_special_offer']: ""; ?>
<?php $actStatus                    = ($isAct) ? $activity['act_status']: ""; ?>
<?php $actStreet                    = ($isAct) ? $activity['act_street']: ""; ?>
<?php $actCity                      = ($isAct) ? $activity['act_city']: ""; ?>
<?php $actZipcode                   = ($isAct) ? $activity['act_zipcode']: ""; ?>
<?php $actCountry                   = ($isAct) ? $activity['act_country']: ""; ?>

<div class="page-title">
    <?php if ($isAct): ?>Modification d'activité<?php else: ?>Création d'activité<?php endif; ?>
</div>

<form method="post" action="<?php if ($isAct): ?>updateActivity<?php else: ?>createActivity<?php endif; ?>">
    <div class="field">
        <label for="act_title">Titre</label>
        <div class="control">
            <input class="input" type="text" name="act_name" value="<?php echo $actName; ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="cat_id">Catégorie (Optionel)</label>
        <div class="control">
            <div class="select">
                <select class="select" name="cat_id">
                    <option value="">Selectionnez une catégorie</option>
                    <?php if (isset($categories)) : ?>
                        <?php foreach($categories as $category) : ?>
                            <option value="<?php echo $category['cat_id']; ?>" <?php if ($actCatId == $category['cat_id']): ?>selected<?php endif; ?>><?php echo $category['cat_name']; ?> </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="field">
        <label for="act_description">Description</label>
        <div class="control">
            <textarea class="textarea" name="act_description" placeholder="Description de l'activité..." required><?php echo $actDescription; ?></textarea>
        </div>
    </div>
    <div class="field">
        <label for="act_resume">Description courte</label>
        <div class="control">
            <textarea class="textarea" name="act_resume" placeholder="Description courte de l'activité..." required><?php echo $actResume; ?></textarea>
        </div>
    </div>
    <div class="field">
        <label for="act_base_price">Prix hors promotion (€)</label>
        <div class="control">
            <input class="input" type="number" min="0.00" step="0.01" name="act_base_price" value="<?php echo $actBasePrice; ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="act_duration">Durée d'une session (min)</label>
        <div class="control">
            <input class="input" type="number" min="1" name="act_duration" value="<?php echo $actDuration; ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="act_participant_nb">Nombre de participants</label>
        <div class="control">
            <input class="input" type="number" min="0" name="act_participant_nb" value="<?php echo $actParticipantNb; ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="act_monitor_nb">Nombre de moniteur requis</label>
        <div class="control">
            <input class="input" type="number" min="0" name="act_monitor_nb" value="<?php echo $actMonitorNb; ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="act_operator_nb">Nombre d'opérateur requis</label>
        <div class="control">
            <input class="input" type="number" min="0" name="act_operator_nb" value="<?php echo $actOperatorNb; ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="act_required_age">Age minimum requis</label>
        <div class="control">
            <input class="input" type="number" min="0" name="act_required_age" value="<?php echo $actRequiredAge; ?>" required>
        </div>
    </div>
    <div class="field">
        <label class="checkbox">
            L'activité fait partie d'une offre spéciale ?
            <input type="checkbox" name="act_is_special_offer" <?php if ($actIsSpecialOffer): ?>checked<?php endif; ?>>
        </label>
    </div>
    <div class="field">
        <div class="control">
            <textarea class="textarea" name="act_description_special_offer" placeholder="Description de l'offre spéciale..."><?php echo $actDescriptionSpecialOffer; ?></textarea>
        </div>
    </div>
    <?php $activitiesStatusMapping = getActivitiesStatusMapping(); ?>
    <div class="field">
        <label for="cat_id">Statut</label>
        <div class="control">
            <div class="select">
                <select class="select" name="act_status">
                    <?php foreach($activitiesStatusMapping as $statusKey => $statusLabel) : ?>
                        <option value="<?php echo $statusKey; ?>" <?php if ($actStatus == $statusKey): ?>selected<?php endif; ?>><?php echo $statusLabel; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="field">
        <label for="act_title">Rue</label>
        <div class="control">
            <input class="input" type="text" name="act_street" value="<?php echo $actStreet; ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="act_title">Ville</label>
        <div class="control">
            <input class="input" type="text" name="act_city" value="<?php echo $actCity; ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="act_title">Code postale</label>
        <div class="control">
            <input class="input" type="text" name="act_zipcode" value="<?php echo $actZipcode; ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="act_title">Pays</label>
        <div class="control">
            <input class="input" type="text" name="act_country" value="<?php echo $actCountry; ?>" required>
        </div>
    </div>
    <?php if ($isAct): ?>
        <input type="hidden" name="act_id" value="<?php echo $activity['act_id']; ?>">
    <?php endif; ?>
    <div class="field buttons">
        <div class="control">
            <button class="button is-link">Valider</button>
        </div>
    </div>
</form>