<div class="activity-card card">
    <div class="card-header">
        <div class="act-name title">
            <?php echo $activity['act_name']; ?>
        </div>
        <div class="act-base-price title">
            <?php echo $activity['act_base_price'] . "€"; ?>
        </div>
    </div>
    <figure class="image is-4by3">
        <img src="<?php echo base_url(). $activity['act_image_1']; ?>" alt="<?php echo $activity['act_name']; ?>">
    </figure>
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
        <div class="activity-pictures">
            <?php if ($activity['act_image_2']): ?>
                <div class="picture">
                    <figure class="image is-4by3">
                        <img src="<?php echo base_url(). $activity['act_image_2']; ?>" alt="<?php echo $activity['act_name']. ' 2'; ?>">
                    </figure>
                </div>
            <?php endif; ?>
            <?php if ($activity['act_image_3']): ?>
                <div class="picture">
                    <figure class="image is-4by3">
                        <img src="<?php echo base_url(). $activity['act_image_3']; ?>" alt="<?php echo $activity['act_name']. ' 3'; ?>">
                    </figure>
                </div>
            <?php endif; ?>
            <?php if ($activity['act_image_4']): ?>
                <div class="picture">
                    <figure class="image is-4by3">
                        <img src="<?php echo base_url(). $activity['act_image_4']; ?>" alt="<?php echo $activity['act_name']. ' 4'; ?>">
                    </figure>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-footer">
        <div class="buttons">
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
<?php if ($alreadyDoneActivity || $comments): ?>
    <div class="activity-comments-title">
        Commentaires
    </div>
<?php endif; ?>
<?php if ($alreadyDoneActivity): ?>
    <form method="post" class="commentForm" action="addActivityComment" enctype="multipart/form-data">
        <div class="add-comment">
            <article class="media">
                <div class="media-content">
                    <div class="field">
                        <label for="com_text">Commentaire</label>
                        <p class="control">
                            <textarea class="textarea com_text" name="com_text" placeholder="Ajouter un commentaire" required></textarea>
                        </p>
                    </div>
                    <div class="field">
                        <label for="com_img">Image</label>
                        <div class="control">
                            <input type="file" name="com_picture_path" id="com_img">
                        </div>
                    </div>
                    <input type="hidden" name="act_id" value="<?php echo $activity['act_id']; ?>">
                    <input type="hidden" name="usr_id" value="<?php echo $_SESSION['user']['id']; ?>">
                    <div class="field buttons">
                        <div class="control">
                            <button class="button is-link">Valider</button>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </form>
<?php endif; ?>

<div class="comments">
    <?php foreach ($comments as $comment): ?>
        <article class="media card comment-card comment-card-<?php echo $comment['com_id']; ?>">
            <input type="hidden" name="comment_usr_id" value="<?php echo $comment['usr_id']?>">
            <figure class="media-left">
                <p class="image is-64x64">
                    <?php if ($comment['usr_profile_picture']): ?>
                        <img src="<?php echo base_url(). $comment['usr_profile_picture']; ?>">
                    <?php else: ?>
                        <img src="https://bulma.io/images/placeholders/128x128.png">
                    <?php endif; ?>
                </p>
            </figure>
            <div class="media-content">
                <div class="content">
                    <p>
                        <strong><?php echo $comment['usr_firstname'] . " " .$comment['usr_lastname']; ?></strong>
                        <br><?php echo $comment['com_text']; ?>
                    </p>
                    <?php if ($comment['com_picture_path']): ?>
                        <div class="image comment-picture">
                            <img src="<?php echo base_url() . $comment['com_picture_path']; ?>">
                        </div>
                    <?php endif; ?>
                    <p>
                        <small><?php echo formatDateAndTime($comment['com_created_at'])?></small>
                    </p>
                </div>
                <?php if (isset($comment['comments'])): ?>
                    <div class="second-level-comment">
                        <?php foreach ($comment['comments'] as $secondLevelComment): ?>
                                <article class="media card comment-card comment-card-<?php echo $secondLevelComment['com_id']; ?>">
                                    <figure class="media-left">
                                        <p class="image is-64x64">
                                            <?php if ($secondLevelComment['usr_profile_picture']): ?>
                                                <img src="<?php echo base_url(). $secondLevelComment['usr_profile_picture']; ?>">
                                            <?php else: ?>
                                                <img src="https://bulma.io/images/placeholders/128x128.png">
                                            <?php endif; ?>
                                        </p>
                                    </figure>
                                    <div class="media-content">
                                        <div class="content">
                                            <p>
                                                <strong><?php echo $secondLevelComment['usr_firstname'] . " " .$secondLevelComment['usr_lastname']; ?></strong>
                                                <br><?php echo $secondLevelComment['com_text']; ?>
                                            </p>
                                            <p>
                                                <small><?php echo formatDateAndTime($secondLevelComment['com_created_at'])?></small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <?php if (isCurrentUserAdmin() || isCurrentUserCustomer() && $_SESSION['user']['id'] == $secondLevelComment['usr_id'] || isCurrentUserCustomer() && $_SESSION['user']['id'] == $comment['usr_id']): ?>
                                            <button class="delete delete-comment" id="delete-comment-<?php echo $secondLevelComment['com_id']; ?>"></button>
                                        <?php endif; ?>
                                    </div>
                                </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (!isCurrentUserNotLoggedIn()): ?>
                    <a class="show-comment-form" id="show-comment-form-<?php echo $comment['com_id']?>">Répondre</a>
                    <form method="post" class="comment-form comment-form-<?php echo $comment['com_id'] ?> hidden" action="addActivityCommentLevel2" onsubmit="event.preventDefault(); return formValidate(<?php echo $comment['com_id'] ?>);">
                        <div class="add-comment">
                            <article class="media">
                                <div class="media-content">
                                    <div class="field">
                                        <p class="control">
                                            <textarea class="textarea com_text" name="com_text" placeholder="Ajouter un commentaire" required></textarea>
                                        </p>
                                    </div>
                                    <input type="hidden" name="com_commented_com_id" value="<?php echo $comment['com_id']; ?>">
                                    <input type="hidden" name="act_id" value="<?php echo $activity['act_id']; ?>">
                                    <input type="hidden" name="usr_id" value="<?php echo $_SESSION['user']['id']; ?>">
                                    <div class="field buttons">
                                        <div class="control">
                                            <button class="button is-link">Valider</button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
            <div class="media-right">
                <?php if (isCurrentUserAdmin() || isCurrentUserCustomer() && $_SESSION['user']['id'] == $comment['usr_id']): ?>
                    <button class="delete delete-comment" id="delete-comment-<?php echo $comment['com_id']; ?>"></button>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<script>
    $('.show-comment-form').click(function () {
       let comId = this.id.replace('show-comment-form-','');
       $('.comment-form-' + comId).toggleClass('hidden');
       if (this.innerHTML === 'Répondre'){
           this.innerHTML = 'Masquer';
       }else{
           this.innerHTML = 'Répondre';
       }
    });

    $('.delete-comment').click(deleteComment);

    function deleteComment(){
        let comId = this.id.replace('delete-comment-','');
        if (confirm( "Êtes-vous sûr de vouloir ce commentaire ?" )) {
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("ActivityController/deleteComment")?>',
                data: {comId:comId,actId:'<?php echo $activity['act_id']; ?>'},
                success: function (data) {
                    let response = JSON.parse(data);
                    if(response.status === 'valid'){
                        $('.comment-card-' + response.com_id).hide();
                    }else{
                        alert(response.message);
                    }
                },
            });
        }
    }

    function formValidate(comId){
        let data = $(".comment-form-"+ comId).serialize();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url("ActivityController/addActivityCommentLevel2")?>',
            data: data,
            success: function (data) {
                let response = JSON.parse(data);
                if(response.status === 'valid'){
                    let comment = response.comment[0];

                    let template = '<article class="media card comment-card comment-card-' + comment.com_id + '">\n'+
'                                    <figure class="media-left">\n'+
'                                        <p class="image is-64x64">\n';
                    if (comment.usr_profile_picture !== undefined){
                        template +=         '<img src="<?php echo base_url() ?>' + comment.usr_profile_picture + '">\n';
                    }else{
                        template +=         '<img src="https://bulma.io/images/placeholders/128x128.png">\n';
                    }

                    let commentCreatedAt = formatDateTime(comment.com_created_at);

                    template +=         '</p></figure><div class="media-content"><div class="content"><p><strong>'+ comment.usr_firstname + " " + comment.usr_lastname + '</strong>\n'+ '<br>' + comment.com_text +'\n'+
'                                            </p><p><small>' + commentCreatedAt + '</small></p></div></div><div class="media-right"><button class="delete delete-comment" id="delete-comment-' + comment.com_id + '"></button></div></article>';

                    $( ".comment-card-" + comId + " .media-content .second-level-comment").append(template);

                    $('#delete-comment-' + comment.com_id).click(deleteComment);
                }else{
                    alert(response.message);
                }
            },
        });
    }

    function formatDateTime(createdAt){
        let createdAtDate = new Date(createdAt);
        let timestamp = createdAtDate.setTime(createdAtDate.getTime() + (2*60*60*1000));
        let date = new Date(timestamp);

        return 'Le ' + ("0" + date.getDate()).slice(-2) + '/' + ("0" + date.getMonth()).slice(-2) + '/' + date.getFullYear() + ' à ' + ("0" + date.getHours()).slice(-2) + ':' + ("0" + date.getMinutes()).slice(-2) + 'h';
    }
</script>
