<form class="card menu-item-card" method="post" action="update">
    <div class="card-header">
        <div class="menu-title">
            <?php echo $menu['men_name']; ?>
        </div>
    </div>
    <div class="card-content">
        <?php foreach ($categories as $category): ?>
            <div class="category-line field">
                <?php $catMenuIndex = $category['mcl_index'] ? $category['mcl_index'] : 0 ?>
                <div class="cat-name">
                    <div class="label">Nom</div>
                    <div class="value">
                        <?php echo $category['cat_name']; ?>
                    </div>
                </div>
                <div class="mcl-index">
                    <div class="label">Index</div>
                    <div class="value">
                        <input type="number" name="cat_ids[<?php echo $category['cat_id']; ?>][mcl_index]" value="<?php echo $catMenuIndex; ?>"/>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (!$menu['men_is_top_menu']): ?>
            <div class="field checkbox-is-top-menu">
                <label class="checkbox">
                    <input type="checkbox" name="is_top_menu">
                    Menu Principal (Cette action remplacera le menu principal).
                </label>
            </div>
        <?php endif; ?>
        <input type="hidden" name="men_id" value="<?php echo $menu['men_id'] ?>">
        <div class="field buttons">
            <div class="control">
                <button class="button is-link">Valider</button>
            </div>
        </div>
    </div>
</form>