<?php //@TODO Activities status :  ?>
<?php //@TODO active : if planned ?>
<?php //@TODO private : on create if no category ?>
<?php //@TODO unavailable : on create if category ?>
<?php //@TODO allow admin status management ?>

<?php $activitiesStatusMapping      = getActivitiesStatusMapping(); ?>
<?php $activitiesStatusColorMapping = getActivitiesStatusColorMapping(); ?>

<div class="card-container activity-list">
    <?php if (isset($activities)) : ?>
        <?php foreach ($activities as $activity) : ?>
            <div class="card">
                <div class="card-header">
                    <a class="title is-4" href="<?php echo base_url("ActivityController/seeActivity"); ?>?id=<?php echo $activity['act_id']; ?>"><?php echo $activity['act_name']; ?></a>
                    <div class="act-status is-6">
                        <div class="led <?php echo $activitiesStatusColorMapping[$activity['act_status']]; ?>">

                        </div>
                        <div class="state"><?php echo $activitiesStatusMapping[$activity['act_status']]; ?></div>
                    </div>
                </div>
                <a class="card-image" href="<?php echo base_url("ActivityController/seeActivity"); ?>?id=<?php echo $activity['act_id']; ?>">
                    <figure class="image is-4by3">
                        <img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder image">
                    </figure>
                </a>
                <div class="card-content">
                    <div class="act_resume row">
                        <?php echo $activity['act_resume']; ?>.
                    </div>
                </div>
                <div class="card-footer">
                    <div class="buttons">
                        <?php //@TODO remove palnActivity if user AND show only active activities if user ?>
                        <?php if (isCurrentUserAdmin()): ?>
                            <a class="button is-link" href="<?php echo base_url("AdminActivityController/updateActivity"); ?>?id=<?php echo $activity['act_id']; ?>">Modifier l'activité</a>
                            <a class="button is-link" href="<?php echo base_url('AdminActivityController/planActivity') ?>?id=<?php echo $activity['act_id']; ?>" class="button">Plannifier</a>
                        <?php endif; ?>
                        <a class="button is-link" href="<?php echo base_url("PlanningController/seeActivityPlanning"); ?>?id=<?php echo $activity['act_id'];?>">Voir le planning</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
