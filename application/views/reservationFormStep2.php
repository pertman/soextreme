<div class="page-title">
    Réservation Etape 2
</div>

<?php if (!$quote = $_SESSION['current_quote']): ?>
    <?php redirect('/', 'refresh'); ?>
<?php endif; ?>

<?php $totalPrice = 0; ?>

<div class="session-description">
    <div class="session-name"><?php echo $quote['activity']['act_name']; ?></div>
    <div class="session-date"><?php echo $quote['date']; ?></div>
    <div class="session-hours"><?php echo $quote['time']; ?></div>
</div>

<div class="reservation-tickets">
    <?php foreach ($quote['participants'] as $key => $participant): ?>
    <?php $totalPrice += $participant['price']; ?>
        <div class="ticket">
            <div class="ticket-column">
                <div class="name"><?php echo strtoupper($participant['usr_lastname']) . " " . $participant['usr_firstname']?></div>
                <div class="age"><?php echo $participant['usr_age'] . " ans"?></div>
            </div>
            <div class="ticket-column">
                <div class="price"><?php echo $participant['price'] . " €"?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php $_SESSION['current_quote']['total'] = $totalPrice; ?>

<div class="reservation-total"><?php echo "Total : ". $totalPrice . "€"?></div>

<form class="reservationForm2" method="post" action="reservationStep3">
    <div class="bank-infomations">
        <?php //@TODO PAYPAL ?>
    </div>
    <div class="buttons">
        <div class="field">
            <div class="control">
                <button class="button validate-reservation-form hidden is-link">Valider</button>
            </div>
        </div>
    </div>
</form>