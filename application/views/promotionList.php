<div class="page-title">
    Liste des promotions
</div>

<?php foreach ($promotions as $promotion): ?>
    <?php $promoId = $promotion['pro_id']; ?>
    <div class="card promo-card">
        <div class="card-content">
            <div class="promo-title">
                <span class="promo-id"><?php echo $promoId; ?>.</span>
                <?php echo $promotion['pro_name']; ?>
            </div>
            <div class="buttons">
                <a class="button update-button is-link" href="<?php echo base_url("AdminPromotionController/updatePromotion"); ?>?id=<?php echo $promoId; ?>">Modifier</a>
                <button class="button delete-button is-link" id="delete-promo-<?php echo $promoId; ?>">Supprimer</button>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
    $('.delete-button').click(function () {
        let promoId = this.id.replace('delete-promo-','');
        if ( confirm( "Êtes-vous sûr de vouloir supprimer la promotion n° " + promoId + "  ?" ) ) {
            window.location.replace("<?php echo base_url("AdminPromotionController/deletePromotion"); ?>?id=" + promoId);
        }
    });
</script>
