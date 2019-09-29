<script>
var urlReservationStep1 = '<?php if (isCurrentUserCustomer()): ?><?php echo base_url(); ?>ReservationController/reservationStep1/json<?php else: ?>../AdminActivityController/modifyPlanning<?php endif; ?>';
var urlReservationStep2 = '<?php echo base_url(); ?>ReservationController/reservationStep2/json';
var urlReservationStep3 =  '<?php echo base_url(); ?>ReservationController/reservationStep3/json';

</script>

<div class="page-title">
    <?php echo "Planning " . $activity['act_name']; ?>
</div>

<?php //@TODO remove if month view ?>
<div class="fc-button-group zoom-buttons">
    <button type="button" class="fc-button fc-button-primary zoom-in-button" aria-label="zoom-in">
        <i class="fas fa-plus"></i>
    </button>
    <button type="button" class="fc-button fc-button-primary zoom-out-button" aria-label="zoom-out">
        <i class="fas fa-minus"></i>
    </button>
</div>

<div id="calendar" class="calendar-activity-container"></div>

<div id="modal-planning" class="modal event-modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Choisir un créneau horaire</p>
            <button class="delete close-event-modal" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
			<ul class="steps is-narrow is-medium is-centered has-content-centered ariane">
			  <li class="steps-segment has-gaps is-active">
			    <span class="steps-marker">
			    	<span class="icon">
			    	  <i class="far fa-calendar-check"></i>
			    	</span>
			    </span>
			    <div class="steps-content">
			    	<p class="heading">Etape 1</p>
			    </div>
			  </li>
			  <li class="steps-segment  has-gaps is-hollow">
				<span class="steps-marker">
				  <span class="icon">
					<i class="fa fa-user"></i>
				  </span>
				</span>
				<div class="steps-content">
				  <p class="heading">Etape 2</p>
				</div>
			  </li>
			  <li class="steps-segment  has-gaps is-hollow">
				<span class="steps-marker">
				  <span class="icon">
					<i class="fas fa-money-bill-wave"></i>
				  </span>
				</span>
				<div class="steps-content">
				  <p class="heading">Paiement</p>
				</div>
			  </li>
			 
			</ul>
            <form class='slot-form' action='<?php if (isCurrentUserCustomer()): ?>../ReservationController/reservationStep1/json<?php else: ?>../AdminActivityController/modifyPlanning<?php endif; ?>' method='post'>
                <?php if(isCurrentUserCustomer()): ?>
                    <div class="time-slots"></div>
                    <input type="hidden" name="event_modal_time" class="event_modal_time">
                    <input type="hidden" name="event_modal_date" class="event_modal_date">
                    <input type="hidden" name="event_modal_price" class="event_modal_price">
                    <input type="hidden" name="event_modal_promotion_ids" class="event_modal_promotion_ids">
                <?php endif; ?>
                <?php if (isCurrentUserAdmin()): ?>
                    <div class="planning-reservations"></div>
                <?php endif; ?>
                <input type="hidden" name="event_modal_pla_id" class="event_modal_pla_id">
                <input type="hidden" name="event_modal_tsl_id" class="event_modal_tsl_id">
            </form>
        </section>

        <footer class="modal-card-foot">
            <div class="buttons">
                <button type="button" class="button is-primary action-event-modal"></button>
                <button class="button close-event-modal">Annuler</button>
            </div>
        </footer>
    </div>
</div>


<div id="modal-step1" class="modal event-modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Choisir les participants</p>
            <button class="delete close-event-modal" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
			
			<ul class="steps is-narrow is-medium is-centered has-content-centered ariane">
			  <li class="steps-segment has-gaps ">
			    <span class="steps-marker">
			    	<span class="icon">
			    	  <i class="far fa-calendar-check"></i>
			    	</span>
			    </span>
			    <div class="steps-content">
			    	<p class="heading">Etape 1</p>
			    </div>
			  </li>
			  <li class="steps-segment  has-gaps is-hollow is-active">
				<span class="steps-marker">
				  <span class="icon">
					<i class="fa fa-user"></i>
				  </span>
				</span>
				<div class="steps-content">
				  <p class="heading">Etape 2</p>
				</div>
			  </li>
			  <li class="steps-segment">
				<span class="steps-marker">
				  <span class="icon">
					<i class="fas fa-money-bill-wave"></i>
				  </span>
				</span>
				<div class="steps-content">
				  <p class="heading">Paiement</p>
				</div>
			  </li>
			</ul>

			<div class="session-description">
				<div class="row name">
					<div class="session-label">Activité:</div>
					<div class="session-value step1-activity"></div>
				</div>
				<div class="row date">
					<div class="session-label">Date:</div>
					<div class="session-value step1-date"></div>
				</div>
				<div class="row hours">
					<div class="session-label">Heures:</div>
					<div class="session-value step1-time"></div>
				</div>
				<div class="row price">
					<div class="session-label">Prix hors réduction age :</div>
					<div class="prices session-value">
						<div class="price"></div>
					</div>
				</div>
			</div>

			<div class="field">
				<label for="participant_nb">Nombre de participant</label>
				<div class="control">
					<div class="select">
						<select class="select select-participant-nb" name="participant_nb">
							
						</select>
					</div>
				</div>
			</div>
			
			<div class="buttons">
				<button class="button is-link validate-participant-nb">Valider</button>
			</div>
			
			<form class="reservationForm1" method="post">
				<div id="msgValidity" class="has-text-red">
				</div>
				<div class="participants">
				</div>
				
				<div class="buttons">
					<div class="field">
						<div class="control">
							<button type="button" class="button validate-reservation-form hidden is-link">Valider</button>
						</div>
					</div>
				</div>
			</form>
        </section>

        <footer class="modal-card-foot">
            <div class="buttons">
                <button type="button" class="button is-primary action-event-modal" disabled="disabled"></button>
                <button class="button close-event-modal">Annuler</button>
            </div>
        </footer>
    </div>
</div>


<div id="modal-step2" class="modal event-modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Choisir les participants</p>
            <button class="delete close-event-modal" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
			<ul class="steps is-narrow is-medium is-centered has-content-centered ariane">
			  <li class="steps-segment has-gaps ">
				<span class="steps-marker">
					<span class="icon">
					  <i class="far fa-calendar-check"></i>
					</span>
				</span>
				<div class="steps-content">
					<p class="heading">Etape 1</p>
				</div>
			  </li>
			  <li class="steps-segment  has-gaps is-hollow ">
				<span class="steps-marker">
				  <span class="icon">
					<i class="fa fa-user"></i>
				  </span>
				</span>
				<div class="steps-content">
				  <p class="heading">Etape 2</p>
				</div>
			  </li>
			  <li class="steps-segment is-active">
				<span class="steps-marker">
				  <span class="icon">
					<i class="fas fa-money-bill-wave"></i>
				  </span>
				</span>
				<div class="steps-content">
				  <p class="heading">Paiement</p>
				</div>
			  </li>
			</ul>
			
			
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

			<div class="reservation-tickets"></div>

			<div class="reservation-total"></div>

			<form class="reservationForm3" method="post" action="<?php echo base_url(); ?>ReservationController/reservationStep3">
				<div class="bank-infomations">
					<input type="hidden" id="id-paypal" name="id_paypal" value="">
				</div>
				<div class="buttons">
					<div class="field">
						<div class="control">
							<button class="button visibility-hidden is-link validate-paypal" style="visibility:hidden">Valider</button>
						</div>
					</div>
				</div>
			</form>
			<div id="bouton-paypal"></div>
			
        </section>

        <footer class="modal-card-foot">
            <div class="buttons">
                <button class="button close-event-modal">Annuler</button>
            </div>
        </footer>
    </div>
</div>
<?php

$events             = array();
$sessionNumber      = 1;
$reservationNumber  = 1;

foreach ($dates as $index => $date){
    if($date['type'] == 'planning'){
        $events[$index]['title']            = $activity['act_name'] . " Session " . $sessionNumber;
        $events[$index]['plaId']            = $date['pla_id'];
        $events[$index]['tslId']            = $date['tsl_id'];
    }else{
        $events[$index]['title']            = $activity['act_name'] . " Reservation " . $reservationNumber;

        $events[$index]['participants']     = $date['participants'];
    }

    $events[$index]['eventType']            = $date['type'];
    $events[$index]['date']                 = $date['date'];
    $events[$index]['start']                = $date['date'] . "T" . $date['start'];
    $events[$index]['end']                  = $date['date'] . "T" . $date['end'];

    if (!isCurrentUserAdmin()){
        $events[$index]['slots']            = $date['slots'];
    }

    $sessionNumber++;
}
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var eventModal          = $('.event-modal');
        var eventModalTitle     = $('.modal-card-title');
        var evenModalPlaId      = $('.event_modal_pla_id');
        var evenModalTslId      = $('.event_modal_tsl_id');
        var actionEventModal    = $('.action-event-modal');

        $('.close-event-modal').click(function () {
            eventModal[0].classList.remove('is-active');
        });
		
		$(document).on ("click", ".close-event-modal", function () {
			$('.reservation-tickets').html('');
			$(".paypal-button").remove();
		});
	
		$('#modal-step1').find('.action-event-modal').prop("disabled", true);

		$('#modal-step3').find('.close-event-modal').click(function () {
			$('#modal-step3').removeClass('is-active');
			$('#modal-step2').addClass('is-active');
			
			//$('#modal-planning').addClass('is-active');
		});
		
		$('#modal-step2').find('.close-event-modal').click(function () {
			$('#modal-step2').removeClass('is-active');
			$('#modal-step1').addClass('is-active');
			//$('#modal-planning').addClass('is-active');
		});
		
		$('#modal-step1').find('.close-event-modal').click(function () {
			$('#modal-step1').removeClass('is-active');
			$('#modal-planning').addClass('is-active');
			$('#modal-step1').find('.action-event-modal').off('click');
			$('.reservationForm1').find('.validate-reservation-form').off('click');
			//$('#modal-planning').addClass('is-active');
		});
		
		$('#modal-planning').find('.close-event-modal').click(function () {
			$('#modal-planning').removeClass('is-active');
			
		});
		
		
        $('#modal-planning').find('.action-event-modal').click(function () {
			var eventModalDate = $('.event_modal_date').val();
			var eventModalTime = $('.event_modal_time').val();
			var eventModalPrice = $('.event_modal_price').val();
			var eventModalPromotionIds = $('.event_modal_promotion_ids').val();
			var finalPrice = 0;
			
			$('#modal-step1').find('.action-event-modal').attr('disabled', 'disabled');

			$.ajax({
				url:  urlReservationStep1,
				type: "POST",
				data:   { 'event_modal_tsl_id' : evenModalTslId.val(), 'event_modal_pla_id' : evenModalPlaId.val(), 'event_modal_date' : eventModalDate, 'event_modal_time' : eventModalTime, 'event_modal_price' : eventModalPrice, 'event_modal_promotion_ids' : eventModalPromotionIds},
				success: function(data){
					var array_reservationStep1 = JSON.parse(data);
					var activity = array_reservationStep1['data']['activity'];
					var divsPromotion = '';
					var divsAvailableTickets = '';

					var reservationStep1IdsPromotion = Object.keys(array_reservationStep1['data']['promotions']);
					reservationStep1IdsPromotion = reservationStep1IdsPromotion.join(',');
					
					$('.modal-card-body').removeClass('is-active');
					$('#modal-step1').find('.promotions').remove();
					$('.row.price').children('.prices').children('.base-price').remove();
					$('.row.price').children('.prices').children('.price').removeClass('special-price');
					$('#modal-step1').addClass('is-active');
					
					
					$('.event-modal').not('#modal-step1').each(function(){
						 $(this).removeClass("is-active");
						 $("#modal-step1").addClass("is-active");
					});
					
					if(array_reservationStep1['data']['promotions'].length !== 0)
					{
						$.each( array_reservationStep1['data']['promotions'], function( key, value ) {
							divsPromotion += '<div class="session-promotion"> '+value+'</div>';
						});
					}
					
					for(let i = 1; i <= array_reservationStep1['data']['availableTickets']; i++)
					{
						divsAvailableTickets += '<option value="'+i+'">'+i+'</option>';
					}
					
					$('.step1-activity').html(activity['act_name']);
					$('.step1-date').html(array_reservationStep1['data']['selectedDate']);
					$('.step1-time').html(array_reservationStep1['data']['selectedTime']);
					$('.row.price').children('.prices').children('.price').html(array_reservationStep1['data']['price']);
					$('.select-participant-nb').html(divsAvailableTickets);
					
					if(array_reservationStep1['data']['promotions'].length !== 0)
					{
						$('.row.price').children('.prices').prepend('<div class="base-price">'+new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(activity['act_base_price'])+'</div>');
						$('.row.price').children('.prices').children('.price').addClass('special-price').append(' €');
						
						$('.row.price').after('<div class="row promotions">'+        
					'       		<div class="session-label">Promotions appliquées :</div>'+
					'       		<div class="session-value">'+
									divsPromotion+
					'       		</div>'+
					'         	</div>');
					}
					
					// Sur le clique de validation du modal étape participant
					$('#modal-step1').find('.action-event-modal').click(function () {
						$('.reservationForm1').find('.validate-reservation-form').click();
					});
					
					$('.reservationForm1').find('.validate-reservation-form').click(function () {
						var f = $('.reservationForm1')[0];
						
							if(f.checkValidity()) 
							{
								var reservationStep1Participants = [];
								var test = { usr_firstname : "test", usr_lastname : "test", usr_age : "test", usr_gift_email : "test"};
								reservationStep1Participants.push(test);
								for(let a = 1;$('.select-participant-nb').val() >= a; a++)
								{
									var participant = { usr_firstname : $("input[name^='participants["+a+"][usr_firstname]']").val(), usr_lastname : $("input[name^='participants["+a+"][usr_lastname]']").val(), usr_age : $("input[name^='participants["+a+"][usr_age]']").val(), usr_gift_email : $("input[name^='participants["+a+"][usr_gift_email]']").val()};

									reservationStep1Participants.push(participant);
									
								}
								
								$.ajax({
									url:  urlReservationStep2,
									type: "POST",
									data:   { 'tsl_id' : array_reservationStep1['data']['tslId'], 'pla_id' : array_reservationStep1['data']['plaId'], 'date' : array_reservationStep1['data']['selectedDate'], 'time' : array_reservationStep1['data']['selectedTime'], 'price' : array_reservationStep1['data']['price'], 'promotionIds' : reservationStep1IdsPromotion, 'participants' : reservationStep1Participants, 'testSQD' : 'test', 'participants' : reservationStep1Participants},
									success: function(data2){
										
										var array_reservationStep2 = JSON.parse(data2);
										
										$('#modal-step1').removeClass('is-active');
										$('#modal-step2').addClass('is-active');
										$('#modal-step2').find('.action-event-modal').prop("disabled", true);
										$('.event-modal').not('#modal-step2').each(function(){
											 $(this).removeClass("is-active");
											 $("#modal-step2").addClass("is-active");
										});
										
										$('.row.name').children('.session-value').html(array_reservationStep2['activity']['act_name']);
										$('.row.date').children('.session-value').html(array_reservationStep2['date']);
										$('.row.hours').children('.session-value').html(array_reservationStep2['time']);
										
										var totalPrice = 0;
										var divsTicket = "";
										array_reservationStep2['participants'].shift();
										
										$.each( array_reservationStep2['participants'], function( key, participant ) {
											totalPrice = (parseFloat(totalPrice) + parseFloat(participant['price']));
											var isAgeReduction = participant['promotions'] ? true : false;
											var divsPromotion = "";
											var divsPriceReduction = "";
											var specialPrice = "";
											if(isAgeReduction)
											{
												specialPrice = "special-price";
												
												$.each( participant['promotions'], function( key, promotion ) {
													divsPromotion += '<p class="price-promotion">'+promotion['pro_name']+'</p>';
												});
												
												var divAgeReduction = '<div class="row promotions">'+
														'<div class="ticket-label">'+
															'Promotions appliquées :'+
														'</div>'+
														'<div class="ticket-value">'+
															divsPromotion+
														'</div>'+
													'</div>';
													
													//$('.prices').append('<div class="base-price">'+new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(activity['act_base_price'])+'</div>');
													
												var divsPriceReduction = '<div class="base-price">'+
														new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(array_reservationStep2['activity']['act_base_price'])+
													'</div>';

											}
											//reservation-tickets
											divsTicket += '<div class="ticket">'+
												'<div class="ticket-column left">'+
													'<div class="row name">'+
														'<div class="ticket-label">'+
															'Participant : '+
														'</div>'+
														'<div class="ticket-value">'+
															participant['usr_lastname'].toUpperCase()+' '+participant['usr_firstname']+
														'</div>'+
													'</div>'+
													'<div class="row age">'+
														'<div class="ticket-label">'+
															'Age :'+
														'</div>'+
														'<div class="ticket-value">'+
															participant['usr_age'].toUpperCase()+' ans'+
														'</div>'+
													'</div>'+
													divAgeReduction+
												'</div>'+
												'<div class="ticket-column">'+
													'<div class="prices">'+
														divsPriceReduction+
														'<div class="price '+specialPrice+'">'+
															participant['price']+'€'+
														'</div>'+
													'</div>'+
												'</div>'+
										'</div>';
													
													
										});

										$('.reservation-tickets').append(divsTicket);
										$('.reservation-total').html(new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(totalPrice));
										
										finalPrice = totalPrice;
										
										/********** PAYPAL *********/
										
										paypal.Button.render({
										  env: 'sandbox',
										  commit: true,
										  style: {
											color: 'gold',
											size: 'responsive',
										  },
										  payment: function() {
											var data = {
											  total:totalPrice, 
											  name:array_reservationStep2['activity']['act_name']
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
												$('.reservationForm2').find(".validate-reservation-form").click();
												//$(".validate-paypal").click();
												//location.href = '<?php echo base_url(); ?>';
												$.ajax({
													url:  urlReservationStep3,
													type: "POST",
													data:   { id_paypal : $("#id-paypal").val(), total_price : finalPrice },
													success: function(data3){
														var array_reservationStep3 = JSON.parse(data3);
														location.href = '<?php echo base_url(); ?>';
													},
													error: function(e){
														console.log(e);
														alert('Une erreur est survenue') ;
													}
												});
												
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
									},
									error: function(e){
										console.log(e);
										alert('Une erreur est survenue') ;
									}
								});
								
							} 
							else 
							{
								var modalStep1PrenomValidity = true;
								var modalStep1NomValidity = true;
								var modalStep1AgeValidity = true;
								var modalMessageValidity = "";
								
								for(let e = 0;e <  $('.modal-step1-prenom').length; e++)
								{
									if(!$(".modal-step1-prenom")[e].checkValidity())
										modalMessageValidity += "Le prénom est obligatoire <br />";
									if(!$(".modal-step1-nom")[e].checkValidity())
										modalMessageValidity += "Le nom est obligatoire <br />";
									if(!$(".modal-step1-age")[e].checkValidity())
										modalMessageValidity += "L'âge minimum doit être supérieur à "+$(".modal-step1-age").attr("min")+'<br />';
										
										if(modalMessageValidity != "")
											break;
									
								}
								
								$("#msgValidity").html(modalMessageValidity);

							}
					});

					  
					
					$('.validate-participant-nb').click(function () {
						let participantNb   = $( ".select-participant-nb option:selected" ).text();
						let participants = $('.participants');

						let currentParticipantsCount = $('.participant').length;

						$('#modal-step1').find('.action-event-modal').prop("disabled", false);

						if (currentParticipantsCount === participantNb){
							return true;
						}

						if (currentParticipantsCount > participantNb){
							for (let i = currentParticipantsCount; i > participantNb; i--){
								$('.participant-'+i).remove();
							} 
						}

						if (participantNb > currentParticipantsCount){
							for (let i = currentParticipantsCount; i < participantNb; i++){
								let participantIndex =  parseInt(i) + 1;

								participants.append('<div class="participant participant-' + participantIndex + '">\n'+
				'            <div class="participant-label">Participant n°' + participantIndex + '</div>\n'+
				'            <div class="columns">\n'+
				'                <div class="column">\n'+
				'                    <div class="field">\n'+
				'                        <label for="usr_firstname">Prénom</label>\n'+
				'                        <div class="control">\n'+
				'                            <input type="text" class="input modal-step1-prenom" name="participants[' + participantIndex + '][usr_firstname]" required>\n'+
				'                        </div>\n'+
				'                    </div>\n'+
				'                </div>\n'+
				'                <div class="column">\n'+
				'                    <div class="field">\n'+
				'                        <label for="usr_lastname">Nom</label>\n'+
				'                        <div class="control">\n'+
				'                            <input type="text" class="input modal-step1-nom" name="participants[' + participantIndex + '][usr_lastname]" required data-value-missing=”Translate(‘Required’)”>\n'+
				'                        </div>\n'+
				'                     </div>\n'+
				'                 </div>\n'+
				'            </div>\n'+
				'            <div class="columns">\n'+
				'                <div class="column">\n'+
				'                    <div class="field">\n'+
				'                        <label for="usr_age">Age</label>\n'+
				'                        <div class="control">\n'+
				'                            <input type="number" class="input modal-step1-age" min="'+activity['act_required_age']+'" name="participants[' + participantIndex + '][usr_age]" required>\n'+
				'                        </div>\n'+
				'                    </div>\n'+
				'                </div>\n'+
				'                <div class="column">\n'+
				'                    <div class="field">\n'+
				'                       <label for="usr_gift_email">Email du destinataire du cadeau (Optionnel) </label>\n'+
				'                         <div class="control">\n'+
				'                           <input type="text" class="input" name="participants[' + participantIndex + '][usr_gift_email]">\n'+
				'                        </div>\n'+
				'                    </div>\n'+
				'                </div>\n'+
				'            </div>\n'+
				'        </div>')
							}
						}
					})
				},
				error: function(e){
					console.log(e);
					alert('Une erreur est survenue') ;
				},
				complete: function(e){
					if($('.participant').length > 0)
					{
						if($(".select-participant-nb").children('option').length < $('.participant').length)
						{
							while($(".select-participant-nb").children('option').length < $('.participant').length)
							{
								$('.participant').last().remove();
							}
						}

						$(".select-participant-nb").val($('.participant').length);
					}
				}
			}); 
			
			//$('.slot-form').submit();

        });

        let jsonString = '';
        <?php foreach ($events as $event) { ?>
            jsonString += JSON.stringify(<?php echo json_encode($event); ?>);
            jsonString += ',';
        <?php }?>
        jsonString = jsonString.slice(0, -1);
        let events = $.parseJSON('[' + jsonString + ']');

        var calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid', 'timeGrid', 'list'], // an array of strings!
            locale: 'fr',
            navLinks: true,
            // eventLimit: 1,
            defaultView: $(window).width() < 760 ? 'timeGridDay' : 'timeGridWeek',
            header: { right: $(window).width() < 760 ? 'timeGridDay,today prev,next': 'dayGridMonth,timeGridWeek,timeGridDay,today prev,next' },
            displayEventEnd: true,
            minTime: "08:00:00",
            maxTime: "18:00:00",
            slotDuration: "00:05:00",
            aspectRatio: 1,
            height: "auto",
            eventSources: [
                {
                    events: events,
                    color: '#e38b3f',
                    textColor: 'white'
                }
            ],
            eventClick: function(calEvent, jsEvent, view, resourceObj) {
                if (actionEventModal.hasClass('hidden')){
                    actionEventModal.removeClass('hidden');
                }
                eventModal[0].classList.add('is-active');
                eventModalTitle[0].innerHTML = calEvent.event.title;    
                if(calEvent.event._def.extendedProps.eventType === 'planning'){
                    evenModalPlaId[0].value = calEvent.event._def.extendedProps.plaId;
                    evenModalTslId[0].value = calEvent.event._def.extendedProps.tslId;
                }


                <?php if(isCurrentUserCustomer()): ?>
                    actionEventModal.text("Continuer");
                    let slots = calEvent.event._def.extendedProps.slots;

                    let slotsDiv = $('.time-slots');
                    slotsDiv.empty();

                    let selectedDate = new Date(calEvent.event.start);

                    let day     = ("0" + selectedDate.getDate()).slice(-2);
                    let month   = ("0" + (selectedDate.getMonth() + 1)).slice(-2);
                    let date    = day + '-' + month + '-' + selectedDate.getFullYear();

                    $('.event_modal_date').attr('value', date);

                    let priceDiv;
                    let promotionDiv;

                    for (let i = 0; i < slots.length; i++ ){
                        let value            = slots[i]['start'].slice(0, -3) + ' ' + slots[i]['end'].slice(0, -3);
                        let availableTickets = slots[i]['participantNb'];

                        let basePrice        = slots[i]['base_price'];
                        let price            = slots[i]['price'];
                        let promotions       = slots[i]['promotions'];

                        let isPromotions     = basePrice !== price;

                        let className = '';
                        if (availableTickets === 0){
                            className = 'disabled';
                        }

                        let promotionIds = '';

                        if (isPromotions){
                            priceDiv = '<div class="prices">' +
                                '<div class="base-price">' + basePrice + ' €</div>' +
                                '<div class="price special-price">' + price + ' €</div>' +
                                '</div>';
                            promotionDiv = '<div class="promotions">';

                            promotions = Object.values(promotions);

                            let promotionTitleArray = [];
                            let promotionsIdsArray  = [];

                            promotions.forEach(function (element) {
                                promotionTitleArray.push(element['pro_name']);
                                promotionsIdsArray.push(element['pro_id']);
                            });

                            promotionIds = promotionsIdsArray.join(',');

                            promotionTitleArray.forEach(function(element) {
                                promotionDiv += '<div class="promotion">' + element + '</div>'
                            });

                            promotionDiv += '</div>';
                        }else{
                            priceDiv = '<div class="prices">' +
                                '<div class="price">' + price + ' €</div>' +
                                '</div>';
                            promotionDiv = '<div class="promotions"></div>';
                        }

                        <?php //@TODO create good infobox ?>

                        slotsDiv.append('<label class="checkbox ' + className + '">' +
                            '<div class="row">' +
                            '<span class="time">' + value + '</span>' +
                            '<input type="checkbox" name="' + value + '">' +
                            priceDiv +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="availableTickets">' + availableTickets + ' places disponibles</div>' +
                            '</div>' +
                            promotionDiv +
                            '<input type="hidden" class="promotionIds" value="' + promotionIds + '">' +
                            '</label>');
                    }

                    actionEventModal.attr("disabled", true);

                    $('label.checkbox:not(.disabled)').change(function () {
                        $('label.checkbox.selected').each(function () {
                            $(this).removeClass('selected');
                        });

                        this.classList.toggle('selected');

                        actionEventModal.attr("disabled", false);

                        let selectedCheckboxSpan        = $('.checkbox.selected .time');
                        let priceDiv                    = $('.checkbox.selected .price');
                        let selectedPromotionIdsInput   = $('.checkbox.selected .promotionIds');

                        $('.event_modal_time').attr('value', selectedCheckboxSpan[0].innerHTML.replace(' ','-'));
                        $('.event_modal_price').attr('value', priceDiv[0].innerHTML.replace(' €',''));
                        $('.event_modal_promotion_ids').attr('value', selectedPromotionIdsInput.attr('value'));
                    });
                <?php else: ?>
                    $('.planning-reservations').empty();

                    if(calEvent.event._def.extendedProps.eventType === 'planning'){
                        actionEventModal.text("Modifier");
                    }else{
                        actionEventModal.addClass('hidden');
                        let participants = calEvent.event._def.extendedProps.participants;


                        let participantsDiv = '<div class="participant-nb">Nombre de participants : ' + participants.length + '/<?php echo $activity['act_participant_nb']?></div></div>';

                        participantsDiv += '<div class="participants">';
                        for (let i = 0; i < participants.length; i++ ) {

                            participantsDiv += '<div class="participant">';
                            participantsDiv += '<div class="participant-info">';
                            participantsDiv += '<div class="firstname">' + participants[i]['firstname'] + '</div>';
                            participantsDiv += '<div class="lastname">' + participants[i]['lastname'] + '</div>';
                            participantsDiv += '<div class="age">' + participants[i]['age'] + ' ans</div>';
                            participantsDiv += '</div>';
                            participantsDiv += '<div class="participant-qr-code">';
                            participantsDiv +='<img src="<?php echo base_url().'uploads/tickets/'; ?>' + participants[i]['tic_id']  + '" alt="qrcode">';
                            participantsDiv += '</div>';
                            participantsDiv += '</div>';
                        }

                        participantsDiv += '</div>';

                        $('.planning-reservations').append(participantsDiv);
                    }
                <?php endif; ?>
            }
        });

        calendar.render();

        $('.zoom-in-button').click(function(){
            zoomIn(calendar);
        });

        $('.zoom-out-button').click(function(){
            zoomOut(calendar);
        });

        if (calendar.getOption('slotDuration') === '00:05:00'){
            $('.zoom-in-button').attr('disabled', true);
        }

        if (calendar.getOption('slotDuration') === '01:00:00'){
            $('.zoom-out-button').attr('disabled', true);
        }
    });

    function zoomIn(calendar) {
        let slotDuration = calendar.getOption('slotDuration');

        let newSlotDuration;
        switch (slotDuration) {
            case '00:15:00':
                newSlotDuration = '00:05:00';
                $('.zoom-in-button').attr('disabled', true);
                break;
            case '00:30:00':
                newSlotDuration = '00:15:00';
                break;
            case '01:00:00':
                newSlotDuration = '00:30:00';
                $('.zoom-out-button').attr('disabled', false);
                break;
        }

        calendar.setOption('slotDuration', newSlotDuration)
    }

    function zoomOut(calendar) {
        let slotDuration = calendar.getOption('slotDuration');

        let newSlotDuration;
        switch (slotDuration) {
            case '00:05:00':
                $('.zoom-in-button').attr('disabled', false);
                newSlotDuration = '00:15:00';
                break;
            case '00:15:00':
                newSlotDuration = '00:30:00';
                break;
            case '00:30:00':
                newSlotDuration = '01:00:00';
                $('.zoom-out-button').attr('disabled', true);
                break;
        }

        calendar.setOption('slotDuration', newSlotDuration)
    }
	
	
	
	
</script>