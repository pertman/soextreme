<?php $actIds = array(); ?>
<?php $menIds = array(); ?>

<?php $isCat   = (isset($category)) ? true : false; ?>
<?php $catName = ($isCat) ? $category['cat_name']: ""; ?>

<?php if (isset($cat_activities)): ?>
    <?php $actIds = array_map(function($cat_activities) { return $cat_activities['act_id']; }, $cat_activities);?>
<?php endif; ?>

<?php if (isset($cat_menus)): ?>
    <?php $menIds = array_map(function($cat_menus) { return $cat_menus['men_id']; }, $cat_menus);?>
<?php endif; ?>

<div class="page-title">
    <?php if ($isCat): ?>Modification de catégorie<?php else: ?>Création de catégorie<?php endif; ?>
</div>

<form method="post" action="<?php if ($isCat): ?>updateCategory<?php else: ?>createCategory<?php endif; ?>">
    <div class="field">
        <label for="cat_name">Nom</label>
        <div class="control">
            <input type="text" class="input" name="cat_name" value="<?php echo $catName; ?>" required>
        </div>
    </div>
    <div class="field activity-add-field">
        <label for="cat_id">Activités (Optionnel)</label>
        <div class="control">
            <div class="select is-multiple">
                <select multiple size="4" name="act_ids[]">
                    <option value="">Selectionnez une ou plusieurs activités</option>
                    <?php if (isset($activities)) : ?>
                        <?php foreach($activities as $activity) : ?>
                            <?php $isCurrentCategoryActivity = in_array($activity['act_id'], $actIds) ?>
                            <option value="<?php echo $activity['act_id']; ?>" <?php if ($isCurrentCategoryActivity): ?>selected<?php endif; ?>><?php echo $activity['act_name']; ?><?php if ($activity['cat_id'] && !$isCurrentCategoryActivity): ?> (Déja dans une autre catégorie)<?php endif; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="field menu-add-field">
        <label for="cat_id">Menus (Optionnel)</label>
        <div class="control">
            <div class="select is-multiple">
                <select multiple size="4" name="men_ids[]">
                    <option value="">Selectionnez un ou plusieurs menus</option>
                    <?php if (isset($menus)) : ?>
                        <?php foreach($menus as $menu) : ?>
                            <?php $isCurrentCategoryMenu = in_array($menu['men_id'], $menIds) ?>
                            <option value="<?php echo $menu['men_id']; ?>" <?php if ($isCurrentCategoryMenu): ?>selected<?php endif; ?>><?php echo $menu['men_name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
    <?php if ($isCat): ?>
        <input type="hidden" name="cat_id" value="<?php echo $category['cat_id']?>">
    <?php endif; ?>
    <div class="field">
        <div class="control">
            <div class="buttons">
                <button class="button is-link">Valider</button>
            </div>
        </div>
    </div>
</form>