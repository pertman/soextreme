<footer class="footer has-text-white">
    <div class="footer-container">
        <div class="newsletter">
            <div class="newletter-label">
                Abonnez-vous à la newsletter
            </div>
            <form action="" method="post" class="newsletter-form" onsubmit="event.preventDefault(); return subscriptionFormValidate()">
                <input class="input" type="text" name="new_email" value="" placeholder="Adresse mail">
                <input class="button is-link" type="submit" value="Valider">
            </form>
        </div>
        <?php if (isCurrentUserCustomer()): ?>
            <div class="admin-contact">
                <div class="admin-contact-label">
                    Besoin d'aide ?
                </div>
                <a class="button is-link" href="<?php echo base_url("UserController/contactAdmin"); ?>">Contacter un administrateur</a>
            </div>
        <?php endif; ?>
    </div>
    <div class="columns">	
		<div class="column is-one-quarter"> 
			<h1 class="has-text-weight-bold is-size-4">
				À PROPOS
			</h1>
			<a href="#"><p class="is-5">Qui sommes-nous ?</p></a>
			<a href="#"><p class="is-5">Plan du site</p></a>
			<a href="#"><p class="is-5">Presse</p></a>
		</div>
		<div class="column is-one-quarter"> 
			<h1 class="has-text-weight-bold is-size-4">
				LIENS UTILES
			</h1>
			<a href="#"><p class="is-5">Mentions légales</p></a>
			<a href="#"><p class="is-5">Politique de confidentialité</p></a>
			<a href="#"><p class="is-5">C.G.V</p></a>
		</div>
		<div class="column is-one-quarter"> 
			<h1 class="has-text-weight-bold is-size-4">
				SUPPORT
			</h1>
			<a href="#"><p class="is-5">FAQ - Besoin d'aide ?</p></a>
		</div>
		<div class="column is-one-quarter"> 
			<h1 class="has-text-weight-bold is-size-4">
				NOUS SUIVRE
			</h1>
			<a href="#" class="is-size-2"><i class="is-5 fab fa-facebook-square"></i></a>
			<a href="#" class="is-size-2"><i class="fab fa-instagram"></i></a>
			<a href="#" class="is-size-2"><i class="fab fa-youtube"></i></a>
		</div>
	</div>
</footer>
<script>
    $('.notification .delete').click(function () {
        this.parentElement.classList.add('hidden');
    });

    function subscriptionFormValidate() {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url("UserController/newsletterSubscription")?>',
            data: $(".newsletter-form").serialize(),
            success: function (data) {
                let response = JSON.parse(data);
                if(response.status === 'valid'){
                    $('.newsletter-form').hide();
                    $('.newletter-label')[0].innerText = response.message;
                }else{
                    alert(response.message);
                }
            },
        });
    }
</script>