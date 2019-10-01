<?php if ($promotions): ?>
    <section id="home-1" class="special-offer">
        <div class="bg-1" style="margin-top:-3rem;">
            <div class="row columns is-multiline">
                <div class="column is-12"></div>
                <div class="column is-12 is-hidden-mobile"></div>
                <div class="column is-12">
                    <h1 class="has-text-weight-bold color-orange is-size-4">
                        Nos <span class="has-text-white">Offres spéciales</span>
                        <hr class="hr-1">
                    </h1>
                </div>
                <div class="promotions">
                    <?php foreach ($promotions as $promotion): ?>
                        <div class="promotion">
                            <?php echo $promotion['pro_description']; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="column is-12"></div>
                <div class="column is-12 is-hidden-mobile"></div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if ($activities): ?>
    <section id="home-2" class="home-activities">
        <div class="row columns is-multiline">
            <div class="column is-12"></div>
            <div class="column is-12 is-hidden-mobile"></div>
            <div class="column is-12">
                <h1 class="has-text-weight-bold has-text-black is-size-4">
                    Nos <span class="color-orange">Activités</span>
                    <hr class="hr-1">
                </h1>
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
                                    <?php echo $activity['act_description_special_offer']; ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="buttons full-width-buttons">
                                    <?php if (isCurrentUserAdmin()): ?>
                                        <a class="button is-link" href="<?php echo base_url('AdminActivityController/planActivity') ?>?id=<?php echo $activity['act_id']; ?>" class="button">Plannifier</a>
                                        <a class="button is-link" href="<?php echo base_url("AdminActivityController/updateActivity"); ?>?id=<?php echo $activity['act_id']; ?>">Modifier l'activité</a>
                                    <?php endif; ?>
                                    <a class="button is-link" href="<?php echo base_url("ActivityController/seeActivity"); ?>?id=<?php echo $activity['act_id']; ?>">Voir l'activité</a>
                                    <?php if (isCurrentUserAdmin() || isCurrentUserCustomer() && $activity['act_status'] == 'active'): ?>
                                        <a class="button is-link" href="<?php echo base_url("PlanningController/seeActivityPlanning"); ?>?id=<?php echo $activity['act_id'];?>">Voir le planning</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>


<?php if ($popularActivities): ?>
    <section id="home-3" class="popular-activities">
        <div class="bg-1">
            <div class="row columns is-multiline">
                <div class="column is-12"></div>
                <div class="column is-12 is-hidden-mobile"></div>
                <div class="column is-12">
                    <h1 class="has-text-weight-bold has-text-white is-size-4">
                        Les <span class="color-orange">+</span> populaires
                        <hr class="hr-1">
                    </h1>
                </div>
                <?php foreach ($popularActivities as $activity): ?>
                    <a class="column is-one-quarter" href="<?php echo base_url("ActivityController/seeActivity"); ?>?id=<?php echo $activity['act_id']; ?>">
                        <div class="card large round column is-full-mobile">
                            <div class="card-image ">
                                <figure class="image">
                                    <?php if ($activity['act_image_1']): ?>
                                        <img src="<?php echo base_url().$activity['act_image_1']; ?>" alt="<?php echo $activity['act_name']; ?>">
                                    <?php else: ?>
                                        <img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder image">
                                    <?php endif; ?>
                                </figure>
                            </div>
                            <div class="card-content no-padding">
                                <div class="media">
                                    <div class="media-content">
                                        <div class="column is-12">
                                            <p class="title is-6 has-text-white act-name"><?php echo $activity['act_name'] . " - " . getLevelLabel($activity['act_level']); ?></p>
                                        </div>

										
										<div class="column is-12 has-text-centered">
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
										</div>
                                        <div class="column is-12 has-text-centered">
                                            <div class="note"><?php echo round($activity['act_note_sum'] / $activity['act_note_count'], 2) . "/10"; ?></div>
                                        </div>
                                        <div class="column is-12 has-text-centered no-padding ">
                                            <p class="title is-7 color-grey "><?php echo $activity['act_note_count']; ?> avis</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                <div class="column is-12"></div>
                <div class="column is-12 is-hidden-mobile"></div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if ($lastPictures): ?>
    <section id="home-4">
        <div class="row columns is-multiline">
            <div class="column is-12"></div>
            <div class="column is-12 is-hidden-mobile"></div>
            <div class="column is-12">
                <h1 class="has-text-weight-bold has-text-black is-size-4">
                    Les photos des <span class="color-orange">clients</span>
                    <hr class="hr-1">
                </h1>
            </div>
            <?php foreach ($lastPictures as $picture): ?>
                <div class="column is-one-fifth is-5-mobile is-inline-block">
                    <div class="card large round column is-full-mobile">
                        <div class="card-image ">
                            <figure class="image">
                                <img src="<?php echo base_url().$picture['com_picture_path']; ?>" alt="Image">
                            </figure>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="column is-12"></div>
            <div class="column is-12 is-hidden-mobile"></div>
        </div>
    </section>
<?php endif; ?>

