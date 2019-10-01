<?php $activitiesStatusMapping      = getActivitiesStatusMapping(); ?>
<?php $activitiesStatusColorMapping = getActivitiesStatusColorMapping(); ?>
<?php $actTitle                     = ($filters) ? $filters['act_name'] : ''; ?>
<?php $actLevel                     = ($filters) ? $filters['act_level'] : ''; ?>
<?php $catId                        = ($filters) ? $filters['cat_id'] : ''; ?>
<?php $actParticipantNb             = ($filters) ? $filters['act_participant_nb'] : ''; ?>
<?php $actRequiredAge               = ($filters) ? $filters['act_required_age'] : ''; ?>
<?php $actHandicappedAccessibility  = ($filters) ? $filters['act_handicapped_accessibility'] : ''; ?>
<div class="page-title">
    Liste des activitées
</div>

<div class="buttons">
    <button class="button is-link show-filters"><?php if ($activities): ?>Filtrer<?php else: ?>Masquer<?php endif; ?></button>
</div>

<form action="listActivities" method="post" class="filter-activities-form <?php if ($activities): ?>hidden<?php endif;?>">
    <div class="field">
        <label for="act_name">Nom de l'activité</label>
        <div class="control">
            <input class="input" type="text" name="act_name" value="<?php echo $actTitle; ?>">
        </div>
    </div>
    <div class="field">
        <label for="act_level">Niveau</label>
        <div class="control">
            <div class="select">
                <select class="select" name="act_level">
                    <option value="">Selectionnez une niveau d'activité</option>
                    <option value="beginner" <?php if ($actLevel == 'beginner'): ?>selected<?php endif ?>>Débutant</option>
                    <option value="confirmed" <?php if ($actLevel == 'confirmed'): ?>selected<?php endif ?>>Confirmé</option>
                    <option value="expert" <?php if ($actLevel == 'expert'): ?>selected<?php endif ?>>Expert</option>
                </select>
            </div>
        </div>
    </div>
    <div class="field">
        <label for="cat_id">Catégorie</label>
        <div class="control">
            <div class="select">
                <select class="select" name="cat_id">
                    <option value="">Selectionnez une catégorie</option>
                    <?php if (isset($categories)) : ?>
                        <?php foreach($categories as $category) : ?>
                            <option value="<?php echo $category['cat_id']; ?>" <?php if ($catId == $category['cat_id']): ?>selected<?php endif; ?>><?php echo $category['cat_name']; ?> </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="field">
        <label for="act_participant_nb">Nombre de participants</label>
        <div class="control">
            <input class="input" type="number" min="0" name="act_participant_nb" value="<?php echo $actParticipantNb; ?>">
        </div>
    </div>
    <div class="field">
        <label for="act_required_age">Age minimum requis</label>
        <div class="control">
            <input class="input" type="number" min="0" name="act_required_age" value="<?php echo $actRequiredAge?>">
        </div>
    </div>
    <div class="field">
        <label for="act_handicapped_accessibility">Accessibilité personnes handicapées</label>
        <div class="control">
            <div class="select">
                <select class="select" name="act_handicapped_accessibility">
                    <option value="">Non</option>
                    <option value="yes" <?php if ($actHandicappedAccessibility == 'yes'): ?>selected<?php endif; ?>>Oui</option>
                </select>
            </div>
        </div>
    </div>

    <div class="field buttons">
        <div class="control">
            <button class="button is-link">Valider</button>
        </div>
    </div>
</form>

<div class="card-container activity-list">
    <?php if ($activities) : ?>
        <?php foreach ($activities as $activity) : ?>
            <div class="card activity-list-card">
                <div class="card-header activity-list-card-header">
                    <a class="title is-4" href="<?php echo base_url("ActivityController/seeActivity"); ?>?id=<?php echo $activity['act_id']; ?>"><?php echo $activity['act_name']. " - ". getLevelLabel($activity['act_level']); ?></a>
                    <?php if (isCurrentUserAdmin()): ?>
                        <div class="act-status is-6">
                            <div class="led <?php echo $activitiesStatusColorMapping[$activity['act_status']]; ?>">

                            </div>
                            <div class="state"><?php echo $activitiesStatusMapping[$activity['act_status']]; ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="note">
						
                        <?php if ($activity['act_note_count']): ?>
                            <?php
								$noteOn5 = (round((round($activity['act_note_sum'] / $activity['act_note_count'], 2) * 2) / 10 * 5) / 2);
								$note = explode('.', $noteOn5);
								for($i = 1; $i <= 5; $i++)
								{
									if($i <= $note[0])
									{
										echo '<i class="fas fa-star"></i>';
									}
									elseif(!empty($note[1]))
									{
										if($note[0] + 1 == $i)
											echo '<i class="fas fa-star-half-alt"></i>';
										else
											echo '<i class="far fa-star"></i>';
									}
									else
									{
										echo '<i class="far fa-star"></i>';
									}
								}						
							?>
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
                <div class="card-bottom-container">
                    <div class="card-content">
                        <div class="act_resume row">
                            <?php echo $activity['act_resume']; ?>
                        </div>
                        <?php if ($activity['act_handicapped_accessibility']): ?>
                            <figure class="image is-64x64">
                                <img src="<?php echo base_url().'uploads/handicap-logo'; ?>" alt="handicap-accessibility">
                            </figure>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <div class="buttons full-width-buttons">
                            <?php if (isCurrentUserAdmin()): ?>
                                <a class="button is-link" href="<?php echo base_url('AdminActivityController/planActivity') ?>?id=<?php echo $activity['act_id']; ?>" class="button">Plannifier</a>
                                <a class="button is-link" href="<?php echo base_url("AdminActivityController/updateActivity"); ?>?id=<?php echo $activity['act_id']; ?>">Modifier l'activité</a>
                            <?php endif; ?>
                            <?php if (isCurrentUserAdmin()): ?>
                                <a class="button is-link" href="<?php echo base_url("ActivityController/seeActivity"); ?>?id=<?php echo $activity['act_id']; ?>">Voir l'activité</a>
                            <?php else: ?>
                                <a class="button is-link" href="<?php echo base_url("ActivityController/seeActivity"); ?>?id=<?php echo $activity['act_id']; ?>">Je découvre !</a>
                            <?php endif; ?>
                            <?php if (isCurrentUserAdmin()): ?>
                                <a class="button is-link" href="<?php echo base_url("PlanningController/seeActivityPlanning"); ?>?id=<?php echo $activity['act_id'];?>">Voir le planning</a>
                            <?php endif; ?>
                            <?php if (isCurrentUserCustomer() && $activity['act_status'] == 'active'): ?>
                                <a class="button is-link" href="<?php echo base_url("PlanningController/seeActivityPlanning"); ?>?id=<?php echo $activity['act_id'];?>">Je réserve !</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        Aucune activité trouvée selon les critères selectionnés
    <?php endif; ?>
</div>

<script>
    $('.show-filters').click(function () {
       $('.filter-activities-form').toggleClass('hidden');
       if (this.innerHTML === 'Filtrer'){
           this.innerHTML = 'Masquer';
       }else{
           this.innerHTML = 'Filtrer';
       }
    });
</script>
