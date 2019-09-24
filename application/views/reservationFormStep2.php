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
        <?php //@TODO PAYPAL ?>
		<input type="hidden" id="id-paypal" name="id_paypal" value="">
    </div>
    <div class="buttons">
        <div class="field">
            <div class="control">
                <button class="button validate-reservation-form hidden is-link" style="visibility:hidden">Valider</button>
            </div>
        </div>
    </div>
</form>
<div id="bouton-paypal"></div>

<script>
    paypal.Button.render({
      env: 'sandbox', // Ou 'production',
      commit: true, // Affiche le bouton  "Payer maintenant"
      style: {
        color: 'gold', // ou 'blue', 'silver', 'black'
        size: 'responsive', // ou 'small', 'medium', 'large'
        // Autres options de style disponibles ici : https://developer.paypal.com/docs/integration/direct/express-checkout/integration-jsv4/customize-button/
      },
      payment: function() {
        // On crée une variable contenant le chemin vers notre script PHP côté serveur qui se chargera de créer le paiement
		var data = {
          total:<?php echo formatPrice($totalPrice); ?>, 
		  name:"<?php echo $quote['activity']['act_name']; ?>"
        };
        var CREATE_URL = '<?php echo base_url("PaypalController/createPayment"); ?>';
        // On exécute notre requête pour créer le paiement
        return paypal.request.post(CREATE_URL, data)
          .then(function(data) { // Notre script PHP renvoie un certain nombre d'informations en JSON (vous savez, grâce à notre echo json_encode(...) dans notre script PHP !) qui seront récupérées ici dans la variable "data"
            if (data.success) { // Si success est vrai (<=> 1), on peut renvoyer l'id du paiement généré par PayPal et stocké dans notre data.paypal_reponse (notre script en aura besoin pour poursuivre le processus de paiement)
               return data.paypal_response.id;   
            } else { // Sinon, il y a eu une erreur quelque part. On affiche donc à l'utilisateur notre message d'erreur généré côté serveur et passé dans le paramètre data.msg, puis on retourne false, ce qui aura pour conséquence de stopper net le processus de paiement.
               alert(data.msg);
               return false;   
            }
         });
      },
      onAuthorize: function(data, actions) {
        // On indique le chemin vers notre script PHP qui se chargera d'exécuter le paiement (appelé après approbation de l'utilisateur côté client).
        var EXECUTE_URL = '<?php echo base_url("PaypalController/executePayment"); ?>';
        // On met en place les données à envoyer à notre script côté serveur
        // Ici, c'est PayPal qui se charge de remplir le paramètre data avec les informations importantes :
        // - paymentID est l'id du paiement que nous avions précédemment demandé à PayPal de générer (côté serveur) et que nous avions ensuite retourné dans notre fonction "payment"
        // - payerID est l'id PayPal de notre client
        // Ce couple de données nous permettra, une fois envoyé côté serveur, d'exécuter effectivement le paiement (et donc de recevoir le montant du paiement sur notre compte PayPal).
        // Attention : ces données étant fournies par PayPal, leur nom ne peut pas être modifié ("paymentID" et "payerID").
        var data = {
          paymentID: data.paymentID,
          payerID: data.payerID
        };
        // On envoie la requête à notre script côté serveur
        return paypal.request.post(EXECUTE_URL, data)
          .then(function (data) { // Notre script renverra une réponse (du JSON), à nouveau stockée dans le paramètre "data"
          if (data.success) { // Si le paiement a bien été validé, on peut par exemple rediriger l'utilisateur vers une nouvelle page, ou encore lui afficher un message indiquant que son paiement a bien été pris en compte, etc.
            // Exemple : window.location.replace("Une url quelconque");
            alert("Paiement approuvé ! Merci !");
			$("#id-paypal").val(data.paypal_response.id);
			$(".validate-reservation-form").click();
          } else {
            // Sinon, si "success" n'est pas vrai, cela signifie que l'exécution du paiement a échoué. On peut donc afficher notre message d'erreur créé côté serveur et stocké dans "data.msg".
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