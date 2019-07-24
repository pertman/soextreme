<?php //@TODO check Admin rights for Activity Category And Menu Controller ?>

<?php foreach ($menus as $menu): ?>
    <?php $menuId = $menu['men_id']; ?>
    <div class="card menu-card<?php if ($menu['men_is_top_menu']): ?> is-top-menu<?php endif; ?>">
        <div class="card-content">
            <div class="menu-title">
                <span class="menu-id"><?php echo $menu['men_id']; ?>.</span>
                <?php echo $menu['men_name']; ?>
            </div>
            <div class="buttons">
                <a class="button update-button is-link" href="<?php echo base_url("MenuController/modifyMenu"); ?>?id=<?php echo $menuId?>">Modifier</a>
                <button class="button delete-button is-link" id="delete-menu-<?php echo $menu['men_id']?>"<?php if ($menu['men_is_top_menu']): ?> disabled<?php endif; ?>>Supprimer</button>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
    $('.delete-button').click(function () {
        let menuId = this.id.replace('delete-menu-','');
        if ( confirm( "Êtes-vous sûr de vouloir supprimer le menu n° " + menuId + "  ?" ) ) {
            window.location.replace("<?php echo base_url("MenuController/deleteMenu"); ?>?id=" + menuId);
        }
    });
</script>
