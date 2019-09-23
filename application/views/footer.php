<footer class="footer">
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