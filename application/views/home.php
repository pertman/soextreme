<div class="page-title">
    Accueil
</div>
<div class="main-page">
    <?php if ($promotions): ?>
        <div class="section-title">
            Offres spéciales
        </div>
        <div class="promotions">
            <?php foreach ($promotions as $promotion): ?>
                <div class="promotion">
                    <?php echo $promotion['pro_description']; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if ($activities): ?>
        <div class="section-title">
            Activités
        </div>
        <div class="card-container activity-list">
            <?php if (isset($activities)) : ?>
                <?php foreach ($activities as $activity) : ?>
                    <div class="card activity-list-card">
                        <div class="card-header activity-list-card-header">
                            <a class="title is-4" href="<?php echo base_url("ActivityController/seeActivity"); ?>?id=<?php echo $activity['act_id']; ?>"><?php echo $activity['act_name']. " - ". getLevelLabel($activity['act_level']); ?></a>
                            <div class="note">
                                <?php if ($activity['act_note_count']): ?>
                                    <?php echo round($activity['act_note_sum'] / $activity['act_note_count'], 2) . "/10"; ?>
                                <?php else: ?>
                                    Non evalué
                                <?php endif; ?>
                            </div>
                        </div>

                        <a class="card-image" href="<?php echo base_url("ActivityController/seeActivity"); ?>?id=<?php echo $activity['act_id']; ?>">
                            <figure class="image is-4by3">
                                <?php if ($activity['act_image_1']): ?>
                                    <img src="<?php echo base_url().$activity['act_image_1']; ?>" alt="<?php echo $activity['act_name']; ?>">
                                <?php else: ?>
                                    <img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder image">
                                <?php endif; ?>
                            </figure>
                        </a>
                        <div class="card-content">
                            <div class="act_resume row">
                                <?php echo $activity['act_description_special_offer']; ?>.
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="buttons">
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
    <?php endif; ?>
</div>

