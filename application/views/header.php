<?php
    $firstName    = '';
    $lastName     = '';
    $email        = '';
    $phone        = '';
    if (isset($post)){
        $firstName    = $post['usr_firstname'];
        $lastName     = $post['usr_lastname'];
        $email        = $post['usr_email'];
        $phone        = $post['usr_phone'];
    }
?>

<section class="hero header-hero is-medium is-primary is-bold">
    <div class="hero-body">
	
        <div class="container">
			
            <h1 class="title">
                <img src="<?php echo base_url(); ?>/application/assets/images/logo3.png" alt="icon soextreme" width="50%">
            </h1>
            <h2 class="subtitle">
                Partagez des sensations inoubliables !
            </h2>
        </div>
		<p>
    </p>

    </div>
	<div id="modal-connexion" class="modal has-text-black">
		<div class="modal-background"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title has-text-centered"><img src="<?php echo base_url(); ?>/application/assets/images/logo4.png" alt="icon soextreme" width="225" height="28"></p>
				<button class="delete" aria-label="close"></button>
				
			</header>
			<section class="modal-card-body">
				<div class="columns">
					<div class="column is-4 is-hidden-mobile"></div>
					<div class="column is-one-two has-text-weight-bold has-text-centered">
						<a href="#" class="modal-menu-connexion">Connexion</a>
						<div class="modal-menu is-active-modal"></div>
					</div>
					
					<div class="column is-one-two has-text-weight-bold has-text-centered">
						<a href="#" class="modal-menu-inscription color-grey">Inscription</a>
						<div class="modal-menu"></div>
					</div>
					<div class="column is-4 is-hidden-mobile"></div>
				</div>
				<h1 class="is-size-3 has-text-weight-bold has-text-centered">
					Se connecter
				</h1>
				
				<form method="post" action="<?php echo base_url(); ?>/logincontroller/connect" >
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<div class="control">
								<input type="mail" class="input" name="usr_email" placeholder="Email" required>
							</div>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<div class="control has-icons-right">
								<input type="password" class="input" name="usr_password" placeholder="Mot de passe" required>
								<span class="icon is-right">
									<i class="fas fa-eye-slash"></i>
								</span>
							</div>
							
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<button type="submit" class="button is-primary is-medium is-fullwidth">Connexion</button>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
				</form>
				<div class="is-size-5 has-text-centered subscribe-link-div">
                    <div class="color-grey">Aucun compte ?</div><div class="modal-subscribe-link">S'inscrire</div>
				</div>
			</section>
		</div>
	</div>
	
	
	<div id="modal-inscription" class="modal has-text-black">
		<div class="modal-background"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title has-text-centered"><img src="<?php echo base_url(); ?>/application/assets/images/logo4.png" alt="icon soextreme" width="225" height="28"></p>
				<button class="delete" aria-label="close"></button>
				
			</header>
			<section class="modal-card-body">
				<div class="columns">
					<div class="column is-4 is-hidden-mobile"></div>
					<div class="column is-one-two has-text-weight-bold has-text-centered">
						<a href="#" class="modal-menu-connexion  color-grey">Connexion</a>
						<div class="modal-menu  "></div>
					</div>
					
					<div class="column is-one-two has-text-weight-bold has-text-centered">
						<a href="#" class="">Inscription</a>
						<div class="modal-menu is-active-modal"></div>
					</div>
					<div class="column is-4 is-hidden-mobile"></div>
				</div>
				<h1 class="is-size-3 has-text-weight-bold has-text-centered">
					Création d'un compte
				</h1>
				
				<form method="post" action="<?php echo base_url(); ?>/usercontroller/create">
				
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<div class="control">
								<input type="text" class="input" name="usr_firstname" value="<?php echo $firstName; ?>" placeholder="Prénom" required>
							</div>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<div class="control">
								<input type="text" class="input" name="usr_lastname" value="<?php echo $lastName; ?>" placeholder="Nom" required>
							</div>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<div class="control">
								<input type="mail" class="input" name="usr_email" value="<?php echo $email; ?>" placeholder="Email" required>
							</div>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<div class="control has-icons-right">
								<input type="password" class="input" name="usr_password" placeholder="Mot de passe" required>
								<span class="icon is-right">
									<i class="fas fa-eye-slash"></i>
								</span>
							</div>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<div class="control has-icons-right">
								<input type="password" class="input" name="usr_password_2" placeholder="Répeter mot de passe" required>
								<span class="icon is-right">
									<i class="fas fa-eye-slash"></i>
								</span>
							</div>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<div class="control">
								<input type="text" class="input" name="usr_phone" value="<?php echo $phone; ?>" placeholder="Téléphone" required>
							</div>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<label for="usr_profile_picture">Photo de profil</label>
							<div class="control">
								<input type="file" name="usr_profile_picture" id="usr_profile_picture">
							</div>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<label class="checkbox title is-7 color-grey">
								<input type="checkbox" required>
								J'accepte les termes et les conditions du site
							</label>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					
					<div class="columns">
						<div class="column is-2 is-hidden-mobile"></div>
						<div class="column is-8 is-12-mobile field">
							<button type="submit" class="button is-primary is-medium is-fullwidth">S'inscrire</button>
						</div>
						<div class="column is-2 is-hidden-mobile"></div>
					</div>
					
				</form>
				<div class="is-size-5 has-text-centered connect-link-div">
                    <div class="color-grey">Déjà un compte ?</div><div class="modal-connect-link">Se connecter</div>
				</div>
			</section>

		</div>
	</div>
	
</section>

<script>
    $('.modal-subscribe-link').click(function () {
        $(".modal-menu-inscription").click();
    });

    $('.modal-connect-link').click(function () {
        $('.modal-menu-connexion').click();
    });
</script>