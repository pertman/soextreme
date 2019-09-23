<div class="page-title">
    Paiement par paypal
</div>

<div class="card-container activity-list">
	<!--
	<div class="navbar-item has-dropdown is-hoverable">
		<p class="navbar-link">
			Activités
		</p>
		<div class="navbar-dropdown is-boxed">
			<a href="<?php echo base_url("AdminActivityController/createActivity"); ?>" class="navbar-item">Créer</a>
			<a href="<?php echo base_url("ActivityController/listActivities"); ?>" class="navbar-item">Liste</a>
		</div>
	</div>
	-->
	<!--
	<a href="#">
		<img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-large.png" alt="Buy now with PayPal" /> 
	</a>
	-->
	<div id="bouton-paypal"></div>
  <script>
    paypal.Button.render({
      env: 'sandbox', // Ou 'production',
      commit: true, // Affiche le bouton  "Payer maintenant"
      style: {
        color: 'gold', // ou 'blue', 'silver', 'black'
        size: 'responsive' // ou 'small', 'medium', 'large'
        // Autres options de style disponibles ici : https://developer.paypal.com/docs/integration/direct/express-checkout/integration-jsv4/customize-button/
      },
      payment: function() {
        // On crée une variable contenant le chemin vers notre script PHP côté serveur qui se chargera de créer le paiement
        var CREATE_URL = '<?php echo base_url("PaypalController/createPayment"); ?>';
        // On exécute notre requête pour créer le paiement
        return paypal.request.post(CREATE_URL)
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
  	<!---
	
	CHAMPS NECESSAIRES
	
	CREATE TABLE `paiements` (
	 `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	 `payment_id` varchar(255) NOT NULL,
	 `payment_status` text NOT NULL,
	 `payment_amount` text NOT NULL,
	 `payment_currency` text NOT NULL,
	 `payment_date` datetime NOT NULL,
	 `payer_email` text NOT NULL
	);

	-->
</div>
