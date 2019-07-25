<form method="post" action="createMenu">
    <div class="field">
        <label for="act_title">Titre</label>
        <div class="control">
            <input class="input" type="text" name="men_name" required>
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
                            <option value="<?php echo $category['cat_id']; ?>"><?php echo $category['cat_name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
         </div>
    </div>
    <div class="field">
        <label class="checkbox">
            <input type="checkbox" name="is_top_menu">
            Menu Principal (Cette action remplacera le menu principal).
        </label>
    </div>
    <div class="field">
        <div class="control">
            <button class="button is-link">Valider</button>
        </div>
    </div>
</form>