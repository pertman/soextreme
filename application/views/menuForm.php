<?php $isMen = (isset($menu)) ? true : false; ?>
<?php $menName = ($isMen) ? $menu['men_name']: ""; ?>

<?php if (isset($men_categories)): ?>
    <?php $catIds = array_map(function($men_categories) { return $men_categories['cat_id']; }, $men_categories);?>
<?php endif; ?>

<form method="post" action="<?php if ($isMen): ?>updateMenu<?php else: ?>createMenu<?php endif; ?>">
    <div class="field">
        <label for="act_title">Titre</label>
        <div class="control">
            <input class="input" type="text" name="men_name" value="<?php echo $menName; ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="cat_id">Catégories</label>
        <div class="control">
            <div class="select is-multiple">
                <select multiple size="4" name="cat_ids[]" required>
                    <option value="" disabled>Selectionnez une ou plusieurs catégories</option>
                    <?php if (isset($categories)) : ?>
                        <?php foreach($categories as $category) : ?>
                            <?php $isCurrentMenuCategory = in_array($category['cat_id'], $catIds) ?>
                            <option value="<?php echo $category['cat_id']; ?>" <?php if ($isCurrentMenuCategory): ?>selected<?php endif; ?>><?php echo $category['cat_name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
         </div>
    </div>
    <?php if (!$isMen || ($isMen && $menu['men_is_top_menu'] == 0)): ?>
        <div class="field">
            <label class="checkbox">
                <input type="checkbox" name="is_top_menu">
                Menu Principal (Cette action remplacera le menu principal).
            </label>
        </div>
    <?php endif; ?>
    <?php if ($isMen): ?>
        <input type="hidden" name="men_id" value="<?php echo $menu['men_id']; ?>">
    <?php endif; ?>
    <div class="field">
        <div class="control">
            <button class="button is-link">Valider</button>
        </div>
    </div>
</form>