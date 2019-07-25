<form method="post" action="createCategory">
    <div class="field">
        <label for="cat_name">Nom</label>
        <div class="control">
            <input type="text" class="input" name="cat_name" required>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <a class="is-link add-activities">Ajouter des activités à cette catégorie</a>
        </div>
    </div>
    <div class="field activity-add-field">
        <label for="cat_id">Activités</label>
        <div class="control">
            <div class="select is-multiple">
                <select multiple size="4" name="act_ids[]">
                    <option value="" disabled>Selectionnez une ou plusieurs activités</option>
                    <?php if (isset($activities)) : ?>
                        <?php foreach($activities as $activity) : ?>
                            <option value="<?php echo $activity['act_id']; ?>"><?php echo $activity['act_name']; ?><?php if ($activity['cat_id']): ?> (Déja dans une autre catégorie)<?php endif; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <a class="is-link add-menus">Ajouter cette categorie à un menu</a>
        </div>
    </div>
    <div class="field menu-add-field">
        <label for="cat_id">Menus</label>
        <div class="control">
            <div class="select is-multiple">
                <select multiple size="4" name="men_ids[]">
                    <option value="" disabled>Selectionnez un ou plusieurs menus</option>
                    <?php if (isset($menus)) : ?>
                        <?php foreach($menus as $menu) : ?>
                            <option value="<?php echo $menu['men_id']; ?>"><?php echo $menu['men_name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>

<?php //@TODO TABLEAU MODIF ETC ? ?>

    <div class="field">
        <div class="control">
            <div class="buttons">
                <button class="button is-link">Valider</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('.add-activities').click(function () {
        $('.activity-add-field').toggleClass('show');
    });

    $('.add-menus').click(function () {
        $('.menu-add-field').toggleClass('show');
    });
</script>