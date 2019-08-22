<div class="page-title">
    Activité n° <?php echo $activity["act_id"]; ?>
</div>

<div class="activity-card card">
    <div class="card-header">
        <div class="act-name title">
            <?php echo $activity['act_name']; ?>
        </div>
        <div class="act-base-price title">
            <?php echo $activity['act_base_price'] . "€"; ?>
        </div>
    <?php //@TODO Prix a partir de : check low price promo?>
    </div>
    <div class="card-content">
        <?php if ($category): ?>
            <div class="cat_name row">
                <div class="label">Catégorie:</div>
                <?php echo $category['cat_name']; ?>
            </div>
        <?php endif; ?>
        <div class="act-description row">
            <div class="label">Description:</div>
            <?php echo $activity['act_description']; ?>
        </div>
        <div class="act-description row">
            <div class="label">Durée:</div>
            <?php echo getDurationValueFromMinute($activity['act_duration']); ?>
        </div>
        <?php if (isCurrentUserAdmin()): ?>
            <div class="act-description row">
                <div class="label">Nombre de moniteurs requis:</div>
                <?php echo $activity['act_monitor_nb']; ?>
            </div>
            <div class="act-description row">
                <div class="label">Nombre d'opérateurs requis:</div>
                <?php echo $activity['act_operator_nb']; ?>
            </div>
            <div class="act-status row">
                <div class="label">Statut:</div>
                <?php echo $activity['act_status']; ?>
            </div>
        <?php endif; ?>
        <div class="act-street row">
            <div class="label">Rue:</div>
            <?php echo $activity['act_street']; ?>
        </div>
        <div class="act-street row">
            <div class="label">Ville:</div>
            <?php echo $activity['act_city']; ?>
        </div>
        <div class="act-street row">
            <div class="label">Code Postal:</div>
            <?php echo $activity['act_zipcode']; ?>
        </div>
        <div class="act-street row">
            <div class="label">Pays:</div>
            <?php echo $activity['act_country']; ?>
        </div>
    </div>
        <div class="card-footer">
            <div class="buttons">
                <?php if ($activity['act_status'] == 'active'): ?>
                    <a class="button is-link" href="<?php echo base_url("PlanningController/seeActivityPlanning"); ?>?id=<?php echo $activity['act_id']; ?>">Voir le planning</a>
                <?php endif; ?>
                <?php if (isCurrentUserAdmin()): ?>
                    <a class="button is-link" href="<?php echo base_url("AdminActivityController/updateActivity"); ?>?id=<?php echo $activity['act_id']; ?>">Modifier l'activité</a>
                <?php endif; ?>
            </div>
        </div>
</div>