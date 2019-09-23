<?php //TODO LOGIC IN CONTROLLER ?>
<?php $isPromotion              = (isset($promotion)) ? true : false; ?>
<?php $isPromotionModification  = ($isPromotion && isset($promotion['pro_id'])) ? true : false; ?>

<?php $proName              = ($isPromotion) ? $promotion['pro_name']: ""; ?>
<?php $proDescription       = ($isPromotion) ? $promotion['pro_description']: ""; ?>

<?php $proDiscountType  = ''; ?>
<?php $proDiscountValue = ''; ?>
<?php $dateRange        = ''; ?>
<?php if ($isPromotion): ?>
    <?php if (isset($promotion['pro_discount_type'])): ?>
        <?php $proDiscountType  = $promotion['pro_discount_type']; ?>
        <?php $proDiscountValue = $promotion['pro_discount_value']; ?>
    <?php else: ?>
        <?php $proDiscountType  = ($promotion['pro_discount_fix']) ? 'pro_discount_fix' : 'pro_discount_percent'; ?>
        <?php $proDiscountValue = ($promotion['pro_discount_fix']) ? $promotion['pro_discount_fix'] : $promotion['pro_discount_percent']; ?>
    <?php endif; ?>
    <?php if (isset($promotion['date_range'])): ?>
        <?php $dateRange = $promotion['date_range']; ?>
    <?php else: ?>
        <?php $dateRange        = ($isPromotion && $promotion['pro_date_start'] && $promotion['pro_date_end']) ? $promotion['pro_date_start']. " - " .$promotion['pro_date_end'] : ""; ?>
    <?php endif; ?>
<?php endif; ?>

<?php $startTime            = ($isPromotion) ? $promotion['pro_hour_start'] : ''; ?>
<?php $endTime              = ($isPromotion) ? $promotion['pro_hour_end'] : ''; ?>
<?php $proType              = ($isPromotion) ? $promotion['pro_type']: ""; ?>
<?php $proPriority          = ($isPromotion) ? $promotion['pro_priority']: ""; ?>

<?php $actIds               = array(); ?>
<?php $catIds               = array(); ?>

<?php if(isset($pro_activities)) : ?>
    <?php $actIds = $pro_activities; ?>
<?php endif; ?>

<?php if(isset($promotion['pro_act_ids'])) : ?>
    <?php $actIds = explode(',', $promotion['pro_act_ids']); ?>
<?php endif; ?>

<?php if(isset($pro_categories)) : ?>
    <?php $catIds = $pro_categories; ?>
<?php endif; ?>

<?php if(isset($promotion['pro_cat_ids'])) : ?>
    <?php $catIds = explode(',', $promotion['pro_cat_ids']); ?>
<?php endif; ?>

<?php if ($isAllPromotionsConditionActivated): ?>
    <?php $proCartAmount    = ($isPromotion) ? $promotion['pro_cart_amount']: ""; ?>
    <?php $proCode          = ($isPromotion) ? $promotion['pro_code']: ""; ?>
    <?php $proMaxUse        = ($isPromotion) ? $promotion['pro_max_use']: ""; ?>
    <?php $usrIds           = (isset($pro_users)) ? $pro_users : array(); ?>
<?php endif; ?>

<?php $startDate = ''; ?>
<?php $endDate   = ''; ?>

<?php if ($dateRange): ?>
    <?php if (strpos($dateRange, ' - ') !== false): ?>
        <?php $dateRangeArray = explode(' - ', $dateRange); ?>
        <?php $startDate = $dateRangeArray[0]; ?>
        <?php $endDate   = $dateRangeArray[1]; ?>
    <?php else: ?>
        <?php $startDate = $dateRange; ?>
    <?php endif; ?>
<?php endif; ?>

<?php $proIsMainPage    = ($isPromotion && isset($promotion['pro_is_main_page'])) ? $promotion['pro_is_main_page']: "";?>

<div class="page-title">
    <?php if ($isPromotionModification): ?>Modification de promotion<?php else: ?>Création de promotion<?php endif; ?>
</div>

<form class="promotionForm" method="post" action="<?php if ($isPromotionModification): ?>updatePromotion<?php else: ?>createPromotion<?php endif; ?>">
    <?php if ($isPromotionModification): ?>
        <input type="hidden" name="pro_id" value="<?php echo $promotion['pro_id']; ?>">
    <?php endif; ?>
    <div class="field">
        <label for="pro_name">Titre</label>
        <div class="control">
            <input class="input" type="text" name="pro_name" value="<?php echo $proName; ?>" required>
        </div>
    </div>

    <div class="field">
        <label for="pro_description">Description</label>
        <div class="control">
            <input class="input" type="text" name="pro_description" value="<?php echo $proDescription; ?>" required>
        </div>
    </div>

    <div class="field">
        <label for="pro_discount_type">Type de promotion</label>
        <div class="control">
            <div class="select">
                <select class="select pro_amount_type" name="pro_discount_type" required>
                    <option value="pro_discount_fix" <?php if ($proDiscountType == 'pro_discount_fix'): ?>selected<?php endif; ?>>Montant fixe</option>
                    <option value="pro_discount_percent" <?php if ($proDiscountType == 'pro_discount_percent'): ?>selected<?php endif; ?>> Pourcentage</option>
                </select>
            </div>
        </div>
    </div>

    <div class="field">
        <label for="pro_discount_value">Valeur de la promotion</label>
        <div class="control">
            <input class="input" type="number" min="1" name="pro_discount_value" value="<?php echo $proDiscountValue; ?>" required>
        </div>
    </div>

    <div class="field">
        <label for="pro_priority">Priorité</label>
        <input class="input pro_priority" type="number" min="0" name="pro_priority" value="<?php echo $proPriority; ?>" required>
        <h1 class="is-link">Attention deux promotion de même priorité ne peuvent pas se cumuler</h1>
        <h1 class="is-link">Dans ce cas la promotion la plus avantageuse pour le client sera retenue</h1>
    </div>

    <?php //TODO ADD IS ACTIVE ?>

    <div class="field">
        <label for="pro_type">Type</label>
        <div class="control">
            <div class="select">
                <select class="select pro_type" name="pro_type" required>
                    <option value="age" <?php if ($proType == 'age'): ?>selected<?php endif; ?>>Reduction tranche d'age</option>
                    <option value="other" <?php if ($proType == 'other'): ?>selected<?php endif; ?>>Autre</option>
                </select>
            </div>
        </div>
    </div>

    <div class="field age-field">
        <label for="pro_age_min">Age minimum</label>
        <div class="control">
            <input class="input pro_age_min" type="number" min="1" name="pro_age_min" value="<?php if ($isPromotion): ?><?php echo $promotion['pro_age_min']; ?><?php endif; ?>">
        </div>
    </div>

    <div class="field age-field">
        <label for="pro_age_max">Age maximum</label>
        <div class="control">
            <input class="input pro_age_max" type="number" min="1" name="pro_age_max" value="<?php if ($isPromotion): ?><?php echo $promotion['pro_age_max']; ?><?php endif; ?>">
        </div>
    </div>

    <div class="field other-field explaination-message">
        <h1 class="is-link">Veuillez remplir au moins une des conditions ci dessous</h1>
        <h1 class="is-link">Toutes les conditions se cumulent</h1>
    </div>

    <div class="field other-field">
        <label for="pro_date_start_pro_date_end">Periode</label>
        <div class="buttons">
            <div class="button is-link reset-period">Réinitialiser periode</div>
        </div>
        <input type="date" id="datePicker" name="date_range" value="<?php echo $dateRange; ?>">
    </div>

    <div class="field other-field">
        <label for="pro_hour_start">Heure de début</label>
        <input type="text" class="input pro_hour_start" id="timeStart" name="pro_hour_start" value="">
    </div>

    <div class="field other-field">
        <label for="pro_hour_end">Heure de Fin</label>
        <input type="text" class="input pro_hour_end" id="timeEnd" name="pro_hour_end" value="">
    </div>

    <?php if ($isAllPromotionsConditionActivated): ?>
        <div class="field other-field">
            <label for="pro_cart_amount">Montant minimum du panier</label>
            <div class="control">
                <input class="input" type="number" min="1" name="pro_cart_amount" value="<?php echo $proCartAmount; ?>" >
            </div>
        </div>

        <div class="field other-field">
            <label for="pro_code">Code coupon</label>
            <div class="control">
                <input class="input" type="text" name="pro_code" value="<?php echo $proCode; ?>">
            </div>
        </div>

        <div class="field other-field">
            <label for="pro_max_use">Nombre maximum d'utilisation</label>
            <div class="control">
                <input class="input" type="number" name="pro_max_use" value="<?php echo $proMaxUse; ?>" >
            </div>
        </div>
    <?php endif; ?>

    <div class="field other-field">
        <label for="act_ids[]">Activités</label>
        <div class="control">
            <div class="select is-multiple">
                <select multiple size="4" name="act_ids[]">
                    <option value="" disabled>Selectionnez une ou plusieurs activitées</option>
                    <?php if (isset($activities)) : ?>
                        <?php foreach($activities as $activity) : ?>
                            <?php $isCurrentProActivity = in_array($activity['act_id'], $actIds) ?>
                            <option value="<?php echo $activity['act_id']; ?>" <?php if ($isCurrentProActivity): ?>selected<?php endif; ?>><?php echo $activity['act_name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="field other-field">
        <label for="cat_ids[]">Catégories</label>
        <div class="control">
            <div class="select is-multiple">
                <select multiple size="4" name="cat_ids[]">
                    <option value="" disabled>Selectionnez une ou plusieurs catégories</option>
                    <?php if (isset($categories)) : ?>
                        <?php foreach($categories as $category) : ?>
                            <?php $isCurrentProCategory = in_array($category['cat_id'], $catIds) ?>
                            <option value="<?php echo $category['cat_id']; ?>" <?php if ($isCurrentProCategory): ?>selected<?php endif; ?>><?php echo $category['cat_name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>

    <?php if ($isAllPromotionsConditionActivated): ?>
        <div class="field other-field">
            <label for="usr_ids[]">Utilisateurs</label>
            <div class="control">
                <div class="select is-multiple">
                    <select multiple size="4" name="usr_ids[]">
                        <option value="" disabled>Selectionnez un ou plusieurs utilisateurs</option>
                        <?php if (isset($users)) : ?>
                            <?php foreach($users as $user) : ?>
                                <?php $isCurrentProUser = in_array($user['usr_id'], $usrIds) ?>
                                <option value="<?php echo $user['usr_id']; ?>" <?php if ($isCurrentProUser): ?>selected<?php endif; ?>><?php echo strtoupper($user['usr_lastname']) . " " . $user['usr_firstname']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="field other-field">
        <label class="checkbox">
            <input type="checkbox" <?php if ($proIsMainPage): ?>checked<?php endif; ?> name="pro_is_main_page">
            Afficher sur la page d'accueil
        </label>
    </div>

    <div class="field">
        <div class="control">
            <button class="button is-link">Valider</button>
        </div>
    </div>
</form>

<script>
    const datePicker = bulmaCalendar.attach('#datePicker' ,{
        dateFormat: 'YYYY-MM-DD',
        displayMode: 'inline',
        isRange: true,
        weekStart: 1,
        startDate: '<?php echo $startDate; ?>',
        endDate: '<?php echo $endDate; ?>',
        minuteSteps: '1',
        showFooter: 'false',
        color: '#4462a5',
    });

    <?php if ($dateRange): ?>
        $('#datePicker')[0].value = '<?php echo $dateRange; ?>';
    <?php endif; ?>

    $('#timeStart').timepicker({
        timeFormat: 'HH:mm',
        interval: 10,
        minTime: '6:00',
        maxTime: '22:00',
        defaultTime: '<?php echo $startTime; ?>',
        startTime: '6:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

    $('#timeEnd').timepicker({
        timeFormat: 'HH:mm',
        interval: 10,
        minTime: '6:00',
        maxTime: '22:00',
        defaultTime: '<?php echo $endTime; ?>',
        startTime: '6:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

    if ("<?php echo $proType; ?>" == "other"){
        $('.age-field').hide();
        $('.other-field').show();
    }

    $('.select.pro_type').change(function () {
       let value = $( ".pro_type option:selected" ).val();
       if (value === 'other'){
           $('.age-field').hide();
           $('.other-field').show();
       }
       if (value === 'age'){
           $('.age-field').show();
           $('.other-field').hide();
       }
    });

    $('.reset-period').click(function () {
        $('#datePicker')[0].value = '';
        $('#datePicker')[0].bulmaCalendar.datePicker.clear();
    });
</script>