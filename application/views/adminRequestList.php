<div class="page-title">
    Liste des demandes
</div>

<?php if ($payBackRequests): ?>
    <div class="request-type-label">
        Demandes de remboursement
    </div>
    <?php foreach ($payBackRequests as $payBackRequest): ?>
        <div class="card request-card">
            <div class="card-content">
                <div class="request-res-id">
                    Reservation n°<?php echo $payBackRequest['res_id']; ?>
                </div>
                <div class="request-pay-id">
                    Payment n°<?php echo $payBackRequest['pay_id']; ?>
                </div>
                <div class="request-pay-amount">
                    Somme à rembourser <?php echo formatPrice($payBackRequest['pay_amount']) .'€'; ?>
                </div>
                <div class="request-name">
                    <?php echo $payBackRequest['usr_firstname'] . " " . $payBackRequest['usr_lastname']; ?>
                </div>
                <div class="request-email">
                    <?php echo $payBackRequest['usr_email']; ?>
                </div>
                <div class="request-email">
                    <?php echo $payBackRequest['usr_phone']; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($openRequests): ?>
    <div class="request-type-label">
        Demandes ouvertes
    </div>

    <?php foreach ($openRequests as $openRequest): ?>
        <div class="card request-card">
			<div class="box message-preview">
				<div class="top">
					<div class="avatar">
						<img src="https://placehold.it/128x128">
					</div> 
					<div class="address">
						<div class="name"><?php echo $openRequest['usr_firstname'] . " " . $openRequest['usr_lastname']; ?></div> 
						<div class="email"><?php echo $openRequest['usr_email']; ?></div>
						<div class="email"><?php echo $openRequest['usr_phone']; ?></div>
						<div class="name">Sujet : <?php echo $openRequest['adr_subject']; ?></div>
					</div> 
						<hr> 
					<div class="content">
						<p><?php echo $openRequest['adr_description']; ?>
						</p>
						<br>
						<div class="buttons">
							<button class="button close-request-button is-link" id="close-request-button-<?php echo $openRequest['adr_id']; ?>">Clôturer</button>
						</div>
					</div>
				</div>
			</div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($closedRequests): ?>
    <div class="request-type-label">
        Demandes fermées
    </div>
    <?php foreach ($closedRequests as $closedRequest): ?>
        <div class="card request-card">
            <div class="card-content">
                <div class="request-subject">
                    <?php echo $closedRequest['adr_subject']; ?>
                </div>
                <div class="request-description">
                    <?php echo $closedRequest['adr_description']; ?>
                </div>
                <div class="request-name">
                    <?php echo $closedRequest['usr_firstname'] . " " . $closedRequest['usr_lastname']; ?>
                </div>
                <div class="request-email">
                    <?php echo $closedRequest['usr_email']; ?>
                </div>
                <div class="request-email">
                    <?php echo $closedRequest['usr_phone']; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    $('.close-request-button').click(function () {
        let adrId = this.id.replace('close-request-button-','');
        if ( confirm( "Êtes-vous sûr de vouloir fermer cette demande ?")) {
            window.location.replace("<?php echo base_url("AdminRequestController/closeRequest"); ?>?id=" + adrId);
        }
    });
</script>
