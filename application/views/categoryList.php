<?php foreach ($categories as $category): ?>
    <?php $catId = $category['cat_id']; ?>
    <div class="card category-card">
        <div class="card-content">
            <div class="cat-title">
                <span class="menu-id"><?php echo $category['cat_id']; ?>.</span>
                <?php echo $category['cat_name']; ?>
            </div>
            <div class="buttons">
                <a class="button update-button is-link" href="<?php echo base_url("AdminCategoryController/updateCategory"); ?>?id=<?php echo $catId; ?>">Modifier</a>
                <button class="button delete-button is-link" id="delete-category-<?php echo $catId; ?>">Supprimer</button>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
    $('.delete-button').click(function () {
        let catId = this.id.replace('delete-category-','');
        if ( confirm( "Êtes-vous sûr de vouloir supprimer la categorie n° " + catId + "  ?" ) ) {
            window.location.replace("<?php echo base_url("AdminCategoryController/deleteCategory"); ?>?id=" + catId);
        }
    });
</script>
