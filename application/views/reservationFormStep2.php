<div class="page-title">
    Réservation Etape 2
</div>

<?php if (!$quote = $_SESSION['current_quote']): ?>
    <?php redirect('/', 'refresh'); ?>
<?php endif; ?>

<?php $totalPrice = 0; ?>

<div class="session-description">
    <div class="row name">
        <div class="session-label">Activité:</div>
        <div class="session-value"><?php echo $quote['activity']['act_name']; ?></div>
    </div>
    <div class="row date">
        <div class="session-label">Date:</div>
        <div class="session-value"><?php echo $quote['date']; ?></div>
    </div>
    <div class="row hours">
        <div class="session-label">Heures:</div>
        <div class="session-value"><?php echo $quote['time']; ?></div>
    </div>
</div>

<div class="reservation-tickets">
    <?php foreach ($quote['participants'] as $key => $participant): ?>
        <?php $totalPrice += $participant['price']; ?>
        <?php $isAgeReduction = $participant['promotions'] ? true : false; ?>

        <div class="ticket">
            <div class="ticket-column left">
                <div class="row name">
                    <div class="ticket-label">
                        Participant :
                    </div>
                    <div class="ticket-value">
                        <?php echo strtoupper($participant['usr_lastname']) . " " . $participant['usr_firstname']?>
                    </div>
                </div>
                <div class="row age">
                    <div class="ticket-label">
                        Age :
                    </div>
                    <div class="ticket-value">
                        <?php echo $participant['usr_age'] . " ans"?>
                    </div>
                </div>
                <?php if ($isAgeReduction): ?>
                    <div class="row promotions">
                        <div class="ticket-label">
                            Promotions appliquées :
                        </div>
                        <div class="ticket-value">
                            <?php foreach ($participant['promotions'] as $promotion): ?>
                                <p class="price-promotion">- <?php echo $promotion['pro_name']; ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="ticket-column">
                <div class="prices">
                    <?php if ($isAgeReduction): ?>
                        <div class="base-price"><?php echo formatPrice($quote['activity']['act_base_price']) . " €"?></div>
                    <?php endif; ?>
                    <div class="price <?php if ($isAgeReduction): ?>special-price<?php endif; ?>"><?php echo $participant['price'] . " €"?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php $_SESSION['current_quote']['total'] = $totalPrice; ?>

<div class="reservation-total"><?php echo "Total : ". formatPrice($totalPrice) . "€"?></div>

<form class="reservationForm2" method="post" action="reservationStep3">
    <div class="bank-infomations">
		<input type="hidden" id="id-paypal" name="id_paypal" value="">
    </div>
    <div class="buttons">
        <div class="field">
            <div class="control">
                <button class="button validate-reservation-form hidden is-link">Valider</button>
            </div>
        </div>
    </div>
</form>
<div id="bouton-paypal"></div>

<script>
    paypal.Button.render({
      env: 'sandbox',
      commit: true,
      style: {
        color: 'gold',
        size: 'responsive',
      },
      payment: function() {
		var data = {
          total:<?php echo formatPrice($totalPrice); ?>, 
		  name:"<?php echo $quote['activity']['act_name']; ?>"
        };
        var CREATE_URL = '<?php echo base_url("PaypalController/createPayment"); ?>';
        return paypal.request.post(CREATE_URL, data)
          .then(function(data) {
            if (data.success) {
               return data.paypal_response.id;   
            } else {
               alert(data.msg);
               return false;   
            }
         });
      },
      onAuthorize: function(data, actions) {
        var EXECUTE_URL = '<?php echo base_url("PaypalController/executePayment"); ?>';

        var data = {
          paymentID: data.paymentID,
          payerID: data.payerID
        };

        return paypal.request.post(EXECUTE_URL, data)
          .then(function (data) {
          if (data.success) {
            alert("Paiement approuvé ! Merci !");
			$("#id-paypal").val(data.paypal_response.id);
			$(".validate-reservation-form").click();
          } else {
            alert(data.msg);
          }
        });
      },
      onCancel: function(data, actions) {
        alert("Paiement annulé : vous avez fermé la fenêtre de paiement.");
      },
      onError: function(err) {
        alert("Paiement annulé : une erreur est survenue. Merci de bien vouloir réessayer ultérieurement.");
      }
    }, '#bouton-paypal');
</script>